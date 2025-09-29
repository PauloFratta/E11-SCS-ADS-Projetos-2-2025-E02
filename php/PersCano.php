<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Personalize seu Cano — Tympvc</title>
  <link rel="stylesheet" href="../styles/PersCano.css">
  <style>
    #viewer {
    width: 100%;
    height: 520px;
    background: #ffffff; /* fundo branco */
    border-radius: 12px;
    margin-top: 1.5rem;
    box-shadow: 0 0 16px rgba(16,63,107,0.10);
    border: 2px solid #ca931b;
}

  </style>
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
<br>
  <div class="container-cano">
  <fieldset>
      <legend>Personalize seu Cano</legend>
      <form method="POST" action="">
      <div class="form-grid">
        <div>
          <label for="diametro">Diâmetro externo (mm)</label>
          <input id="diametro" name="diametro" type="number" min="1" value="110">
        </div>
        <div>
          <label for="comprimento">Comprimento (mm)</label>
          <input id="comprimento" name="comprimento" type="number" min="1" value="1000">
        </div>
        <div>
          <label for="espessura">Espessura (mm)</label>
          <input id="espessura" name="espessura" type="number" min="0.5" step="0.1" value="3.6">
        </div>
        <div>
          <label for="quantidade">Quantidade</label>
          <input id="quantidade" name="quantidade" type="number" min="1" value="1">
        </div>
        <div>
          <label for="formato">Formato</label>
          <select id="formato" name="tipo">
            <option value="reta" selected>Reta</option>
            <option value="cotovelo">Cotovelo</option>
            <option value="t">T</option>
          </select>
        </div>
        <div>
          <label for="segments">Suavidade (segments)</label>
          <input id="segments" name="segments" type="number" min="8" max="256" value="64">
        </div>
        <div>
          <label for="cor">Cor</label>
          <select id="cor" name="cor">
            <option value="Branco">Branco</option>
            <option value="Cinza">Cinza</option>
            <option value="Preto">Preto</option>
          </select>
        </div>
      </div>

      <div class="controls">
  <button id="btnAtualizar" class="primary">Atualizar modelo</button>
  <button id="btnReset" class="primary">Reset camera</button>
  <button id="btnVisao3D" class="primary">Visão 3D</button>
  <br>
  <br>  
<button type="submit" class="primary">Adicionar ao Carrinho</button>
  <div class="note">Insira medidas em <strong>mm</strong>. Se selecionar formatos especiais (cotovelo/T) ainda será gerada a versão reta — suporte a junções será implementado separadamente.</div>
      </div>
    <center
      <div id="viewer"></div>
  </center>
  </form>
    </fieldset>

  <footer class="site-footer">
    <div class="footer-container">
      <h2>&copy; 2025 Tympvc. Todos os direitos reservados.</h2>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/three@0.152.2/build/three.min.js"></script>
  <script src="../js/srcipt.js"></script>
  </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $tipo = $_POST['tipo'];
    $comprimento = $_POST['comprimento'];
    $diametro = $_POST['diametro'];
    $cor = $_POST['cor'];
    $espessura = $_POST['espessura'];
    $quantidade = $_POST['quantidade'];

    $conn = new mysqli('localhost', 'root', '', 'pvc_projeto');
    $stmt = $conn->prepare("INSERT INTO cano_personalizado (id_usuario, tipo, comprimento, diametro, cor, espessura, quantidade) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissdi", $id_usuario, $tipo, $comprimento, $diametro, $cor, $espessura, $quantidade);
    $stmt->execute();
    $cano_id = $stmt->insert_id; // pega o ID do cano personalizado recém-criado
    $stmt->close();
    $conn->close();

    // Adiciona o cano personalizado ao carrinho na sessão
    $_SESSION['carrinho']['cano_personalizado'] = [
        'id' => $cano_id,
        'tipo' => $tipo,
        'comprimento' => $comprimento,
        'diametro' => $diametro,
        'cor' => $cor,
        'espessura' => $espessura,
        'quantidade' => $quantidade
    ];

    header('Location: carrinho.php');
    exit;
}
?>
