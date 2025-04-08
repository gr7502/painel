<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Chamadas</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #f4f4f4;
      display: flex;
      height: 100vh;
      width: 100vw;
      align-items: center;
      justify-content: center;
      font-family: 'roboto', sans-serif;
    }

    .container {
      display: flex;
      width: 100%;
      height: 100%;
      background: white;
    }

    .chamada {
      flex: 2;
      background: #6a5acd;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3vw;
      font-weight: bold;
      text-align: center;
      padding: 20px;
    }

    .chamada span {
      font-size: 4vw;
    }

    .logo-container {
      position: absolute;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      text-align: center;
      width: 100%;
    }

    .logo-container img {
      max-width: 250px;
      height: auto;
    }

    .relogio {
      position: absolute;
      bottom: 10px;
      right: 8px;
      font-size: 1.2vw;
      font-weight: bold;
      color: white;
      background: rgba(106, 90, 205, 0.9);
      padding: 15px;
      border-radius: 10px;
      text-align: center;
    }

    .ultimos-chamados {
      flex: 1;
      background: white;
      padding: 20px;
      overflow-y: auto;
    }

    .titulo {
      text-align: center;
      font-size: 2vw;
      font-weight: bold;
      margin-bottom: 15px;
      color: #6a5acd;
    }

    .chamado-card {
      border-left: 5px solid #6a5acd;
      padding: 20px;
      margin-bottom: 15px;
      background: #fff;
      border-radius: 5px;
      font-size: 1.5vw;
    }

    .sem-chamadas {
    text-align: center;
    padding: 20px;
    color: #666;
}

.sem-chamadas img {
    opacity: 0.5;
    margin-bottom: 10px;
}

.sem-chamadas p {
    font-style: italic;
    font-size: 1.5vw;
    justify-items: center;
}
  </style>
</head>

<body>
  <div class="container">
    <div class="logo-container">
      <img src="<?php echo base_url('assets/imagens/Senha.png'); ?>" alt="">
    </div>
    <div class="chamada">
      <div id="mensagemSenha">
        <span>Última chamada:</span> <br>
        <strong id="senhaChamada">Carregando...</strong>
      </div>
    </div>
    <div class="ultimos-chamados">
      <h2 class="titulo">ÚLTIMOS CHAMADOS</h2>
      <div id="ultimos-chamados">
        <?php if (!empty($ultimasChamadas)): ?>
          <?php foreach ($ultimasChamadas as $chamada): ?>
            <div class="chamado-card">
              <div class="info">
                <span class="fw-bold">
                  <?= $chamada['tipo'] === 'senha' ? 'SENHA' : 'PACIENTE' ?>
                </span>
                <span class="numero"><?= $chamada['tipo'] === 'senha' ? $chamada['senha'] : $chamada['paciente'] ?></span>
              </div>
              <div class="destino">
                <span><?= $chamada['tipo'] === 'senha' ? $chamada['guiche'] : $chamada['consultorio'] ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="fw-bold">Nenhuma chamada recente.</p>
        <?php endif; ?>
      </div>
      <div id="relogio" class="relogio"></div>
    </div>
  </div>

  <script src="<?php echo base_url('assets/js/painel_2.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/relogio.js'); ?>"></script>
  <script>
  const API_BASE_URL = "<?= site_url('chamada2') ?>";
</script>
</body>

</html>
