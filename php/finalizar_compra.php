<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Produtos de exemplo
$produtos = [
    1 => ['nome' => 'Conector de tubo de pvc branco', 'preco' => 3.36, 'img' => '../images/products/product-1.png'],
    2 => ['nome' => 'Tubulação de água transparente do PVC', 'preco' => 65.83, 'img' => '../images/products/product-2.png'],
];

// Buscar todos os canos personalizados do usuário
$conn = new mysqli('localhost', 'root', '', 'pvc_projeto');
$id_usuario = $_SESSION['id_usuario'];
$stmt = $conn->prepare("SELECT * FROM cano_personalizado WHERE id_usuario = ? ORDER BY id DESC");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$canos = [];
while ($row = $result->fetch_assoc()) {
    $canos[] = $row;
}
$stmt->close();
$conn->close();

$total = 0;
$preco_cano = 49.90;

// Simula finalização do pedido
$pedido_finalizado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    // Aqui você pode salvar o pedido em uma tabela 'pedidos' se desejar
    // Limpa o carrinho da sessão
    unset($_SESSION['carrinho']);
    $pedido_finalizado = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra | Tympvc</title>
    <link rel="stylesheet" href="../styles/globals.css">
</head>
<body>
    <div class="navbar show-menu">
        <div class="header-inner-content">
            <h1 class="logo">Tym<span>pvc</span></h1>
            <nav>
                <ul>
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="cadastro.php">Cadastro</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="PersCano.php">Personalize seu Cano</a></li>
                </ul>
            </nav>
            <div class="nav-icon-container">
                <a href="carrinho.php"><img src="../images/cart.png"></a>
                <a href="usuario.php"><img src="../images/menu.png" class="menu-button"></a>
            </div>
        </div>
    </div>
    <main>
        <div class="page-inner-content">
            <h3 class="section-title">Finalizar Compra</h3>
            <div class="subtitle-underline"></div>
            <?php if ($pedido_finalizado): ?>
                <div style="background:#d4edda; color:#155724; padding:20px; border-radius:8px; margin-top:24px;">
                    <h4>Pedido realizado com sucesso!</h4>
                    <p>Obrigado por comprar na Tympvc. Em breve você receberá um e-mail com os detalhes do pedido.</p>
                    <a href="../index.html" class="checkout-btn" style="margin-top:16px; display:inline-block;">Voltar para a loja</a>
                </div>
            <?php else: ?>
            <form method="post" action="finalizar_compra.php">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Produtos comuns do carrinho
                        if (!empty($_SESSION['carrinho'])):
                            foreach ($_SESSION['carrinho'] as $id => $qtd):
                                if (!isset($produtos[$id])) continue;
                                $produto = $produtos[$id];
                                $subtotal = $produto['preco'] * $qtd;
                                $total += $subtotal;
                        ?>
                        <tr>
                            <td><img src="<?= $produto['img'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" style="width:60px"></td>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td>R$<?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td><?= $qtd ?></td>
                            <td>R$<?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php
                            endforeach;
                        endif;

                        // Cano personalizado (todos do usuário)
                        if (!empty($canos)):
                            foreach ($canos as $cano):
                                $subtotal_cano = $preco_cano * $cano['quantidade'];
                                $total += $subtotal_cano;
                        ?>
                        <tr style="background:#f9f9f9;">
                            <td colspan="2"><b>Cano Personalizado #<?= $cano['id'] ?></b></td>
                            <td>R$<?= number_format($preco_cano, 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($cano['quantidade']) ?></td>
                            <td>R$<?= number_format($subtotal_cano, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <ul>
                                    <li><b>Tipo:</b> <?= htmlspecialchars($cano['tipo']) ?></li>
                                    <li><b>Comprimento:</b> <?= htmlspecialchars($cano['comprimento']) ?> mm</li>
                                    <li><b>Diâmetro:</b> <?= htmlspecialchars($cano['diametro']) ?> mm</li>
                                    <li><b>Espessura:</b> <?= htmlspecialchars($cano['espessura']) ?> mm</li>
                                    <li><b>Cor:</b> <?= htmlspecialchars($cano['cor']) ?></li>
                                </ul>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        endif;
                        if (empty($_SESSION['carrinho']) && empty($canos)):
                        ?>
                        <tr>
                            <td colspan="5">Seu carrinho está vazio.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="cart-summary">
                    <h4>Total: <span>R$<?= number_format($total, 2, ',', '.') ?></span></h4>
                </div>
                <br>
                <button type="submit" name="confirmar" class="checkout-btn">Confirmar Pedido</button>
            </form>
            <?php endif; ?>
        </div>
    </main>
    <footer class="site-footer" id="Contato">
        <div class="footer-container">
            <br><br>
            <h2>&copy; 2025 Tympvc. Todos os direitos reservados.</h2>
            <br><br><br>
            <ul class="footer-links">
                <li>+55 11 98762-5432</li>
                <li>Tympvc@tympvc.com</li>
            </ul>
        </div>
    </footer>
</body>
</html>