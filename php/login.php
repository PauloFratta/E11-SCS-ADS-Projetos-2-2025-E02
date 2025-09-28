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
                    <li><a href="#">Personalize seu Cano</a></li>
                </ul>
            </nav>
            <div class="nav-icon-container">
                <img src="../images/cart.png">
                <img src="../images/menu.png" class="menu-button">
            </div>
        </div>
    </div>
    <main>
        <br><br><br><br><br><br>
        <Br>
        <fieldset>
  <legend>Login</legend>
  <form method="post" action="login.html" class="form-cadastro">
    <label for="nome">Nome Completo</label>
    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>

    <label for="Senha">Senha</label>
    <input type="password" id="Senha" name="Senha" placeholder="Crie uma senha" required>
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
