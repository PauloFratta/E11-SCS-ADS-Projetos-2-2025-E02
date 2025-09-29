<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
<link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="navbar show-menu">
        <div class="header-inner-content">
            <h1 class="logo">Tym<span>pvc</span></h1>
            <nav>
                <ul>
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../php/cadastro.php">Cadastro</a></li>
                    <li><a href="../php/login.php">Login</a></li>
                    <li><a href="../php/PersCano.php">Personalize seu Cano</a></li>
                </ul>
            </nav>
            <div class="nav-icon-container">
                <a href="../php/carrinho.php"><img src="../images/cart.png"></a>
                <a href="../E11-SCS-ADS-Projetos-2-2025-E02/php/usuario.php"></a><img src="../images/menu.png" class="menu-button"></a>
            </div>
        </div>
    </div>
    <main>
        <br><br><br><br><br><br>
        <Br>
        <fieldset>
  <legend>Login</legend>
  <form method="post" action="login.php" class="form-cadastro">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
    <a href="../php/cadastro.php">Cadastro</a>
    <button type="submit" class="btn-enviar">Login</button>
</form>
</fieldset>
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
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $conn = new mysqli('localhost', 'root', '', 'pvc_projeto');
    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $nome, $senha_hash);
    if ($stmt->fetch() && password_verify($senha, $senha_hash)) {
        $_SESSION['id_usuario'] = $id;
        $_SESSION['nome'] = $nome;
        header('Location: ../php/usuario.php');
        exit;
    } else {
        $erro = "E-mail ou senha inválidos!";
    }
    $stmt->close();
    $conn->close();
}
?>