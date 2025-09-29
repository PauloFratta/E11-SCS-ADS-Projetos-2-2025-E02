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

// Remover cano personalizado do carrinho (do banco)
if (isset($_GET['remover_cano'])) {
    $cano_id = (int)$_GET['remover_cano'];
    $conn = new mysqli('localhost', 'root', '', 'pvc_projeto');
    $stmt = $conn->prepare("DELETE FROM cano_personalizado WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $cano_id, $_SESSION['id_usuario']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: carrinho.php');
    exit;
}

// Remover produto do carrinho (sessão)
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header('Location: carrinho.php');
    exit;
}

// Atualizar quantidade dos produtos (sessão)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantidade'])) {
    foreach ($_POST['quantidade'] as $id => $qtd) {
        $_SESSION['carrinho'][(int)$id] = max(1, (int)$qtd);
    }
    header('Location: carrinho.php');
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Tympeg</title>
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
            <h3 class="section-title">Seu Carrinho</h3>
            <div class="subtitle-underline"></div>
            <div class="cart-table-container">
                <form method="post" action="carrinho.php">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        // Produtos comuns
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
                            <td>
                                <input type="number" name="quantidade[<?= $id ?>]" value="<?= $qtd ?>" min="1" style="width:50px">
                            </td>
                            <td>R$<?= number_format($subtotal, 2, ',', '.') ?></td>
                            <td><a href="?remove=<?= $id ?>" class="remove-btn" style="color:#fff;background:#e74c3c;padding:4px 10px;border-radius:4px;text-decoration:none;">X</a></td>
                        </tr>
                        <?php
                            endforeach;
                        endif;

                        // Cano personalizado (todos do usuário)
                        $preco_cano = 49.90;
                        if (!empty($canos)):
                            foreach ($canos as $cano):
                                $subtotal_cano = $preco_cano * $cano['quantidade'];
                                $total += $subtotal_cano;
                        ?>
                        <tr style="background:#f9f9f9; cursor:pointer;" onclick="document.getElementById('cano-<?= $cano['id'] ?>').style.display = (document.getElementById('cano-<?= $cano['id'] ?>').style.display === 'none' ? 'block' : 'none');">
                            <td colspan="2"><b>Cano Personalizado #<?= $cano['id'] ?></b></td>
                            <td>R$<?= number_format($preco_cano, 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($cano['quantidade']) ?></td>
                            <td>R$<?= number_format($subtotal_cano, 2, ',', '.') ?></td>
                            <td><a href="?remover_cano=<?= $cano['id'] ?>" class="remove-btn" style="color:#fff;background:#e74c3c;padding:4px 10px;border-radius:4px;text-decoration:none;" onclick="event.stopPropagation();">X</a></td>
                        </tr>
                        <tr id="cano-<?= $cano['id'] ?>" style="display:none;">
                            <td colspan="6">
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
                            <td colspan="6">Seu carrinho está vazio.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="submit" class="checkout-btn" style="margin-bottom:16px;">Atualizar Quantidades</button>
                </form>
                <div class="cart-summary">
                    <h4>Total: <span>R$<?= number_format($total, 2, ',', '.') ?></span></h4>
                    <a href="../php/finalizar_compra.php"><button class="checkout-btn" >Finalizar Compra</button></a>
                </div>
            </div>
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
    <script>
    // Garante que o clique no X não abra/feche o detalhe
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>
</html>