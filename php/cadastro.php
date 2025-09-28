<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
<link rel="stylesheet" href="../styles/cadastro.css">
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
                <img src="../images/cart.png">
                <img src="../images/menu.png" class="menu-button">
            </div>
        </div>
    </div>
    <main>
        <Br>
        <fieldset>
  <legend>Cadastro</legend>
  <form method="post" action="cadastro.php" class="form-cadastro">
    <label for="nome">Nome Completo</label>
    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>

    <label for="Senha">Senha</label>
    <input type="password" id="Senha" name="Senha" placeholder="Crie uma senha" required>

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>

    <label for="telefone">Telefone</label>
    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" required>

    <label for="cpf">CPF</label>
    <input type="text" id="cpf" name="cpf" placeholder="Somente números" maxlength="11" required>

    <label for="endereco">Endereço</label>
    <input type="text" id="endereco" name="endereco" placeholder="Rua, número, bairro" required>

    <label for="cidade">Cidade</label>
    <input type="text" id="cidade" name="cidade" placeholder="Digite sua cidade" required>

    <label for="estado">Estado</label>
    <select id="estado" name="estado" required>
      <option value="">Selecione</option>
      <option value="SP">SP</option>
      <option value="RJ">RJ</option>
      <option value="MG">MG</option>
      <option value="RS">RS</option>
      <option value="Outros">Outros</option>
    </select>
    <a href="../php/login.php">Login</a>
    <button type="submit" class="btn-enviar">Cadastrar</button>
  </form>
</fieldset>
<br>
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
