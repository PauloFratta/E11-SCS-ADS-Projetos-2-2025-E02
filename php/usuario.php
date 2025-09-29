<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'pvc_projeto');
$id_usuario = $_SESSION['id_usuario'];

$stmt = $conn->prepare("SELECT nome, email, telefone, cpf, endereco, cidade, estado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nome, $email, $telefone, $cpf, $endereco, $cidade, $estado);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Usuário | Tympvc</title>
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
                <a href="#"><img src="../images/menu.png" class="menu-button"></a>
            </div>
        </div>
    </div>
    <main>
        <div class="page-inner-content">
            <h3 class="section-title">Dados do Usuário</h3>
            <div class="subtitle-underline"></div>
            <div class="user-info" style="background:#fff; border-radius:8px; padding:24px; margin-top:24px; box-shadow:0 2px 8px #0001;">
                <p><b>Nome:</b> <?= htmlspecialchars($nome) ?></p>
                <p><b>E-mail:</b> <?= htmlspecialchars($email) ?></p>
                <p><b>Telefone:</b> <?= htmlspecialchars($telefone) ?></p>
                <p><b>CPF:</b> <?= htmlspecialchars($cpf) ?></p>
                <p><b>Endereço:</b> <?= htmlspecialchars($endereco) ?></p>
                <p><b>Cidade:</b> <?= htmlspecialchars($cidade) ?></p>
                <p><b>Estado:</b> <?= htmlspecialchars($estado) ?></p>
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
</body>
</html>