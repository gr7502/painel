<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Chamadas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --cor-primaria: <?php echo isset($config->primary_color) ? $config->primary_color : '#0047AB'; ?>;
      --cor-primaria-hover: <?php echo isset($config->primary_color) ? adjustBrightness($config->primary_color, -20) : '#003580'; ?>;
      --cor-primaria-transparente: <?php echo isset($config->primary_color) ? adjustBrightness($config->primary_color, 0, 0.1) : 'rgba(0, 71, 171, 0.1)'; ?>;
      --cor-secundaria: #ffffff;
      --cor-texto: #2d2d2d;
      --cor-texto-secundario: #6c757d;
      --sombra: 0 4px 24px rgba(0, 0, 0, 0.08);
      --borda-radius: 16px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background: #f8f9fa;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
      color: var(--cor-texto);
    }

    /* Header Moderno */
    .info-consulta-header {
      background: linear-gradient(135deg, var(--cor-primaria), var(--cor-primaria-hover));
      color: white;
      padding: 1.5rem 0;
      text-align: center;
      box-shadow: 0 4px 24px rgba(0, 71, 171, 0.2);
      position: relative;
      overflow: hidden;
    }

    .info-consulta-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: rgba(255, 255, 255, 0.15);
    }

    #mensagemSenha {
      font-size: 2.5rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin: 0.5rem 0;
    }

    #senhaChamada {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(4px);
      padding: 1rem 2rem;
      border-radius: var(--borda-radius);
      border: 2px solid rgba(255, 255, 255, 0.3);
      display: inline-block;
      transition: all 0.3s ease;
    }

    /* Layout Principal */
    .main-container {
      flex: 1;
      padding: 1.5rem;
    }

    .content-wrapper {
      display: flex;
      gap: 2rem;
      max-width: 1400px;
      margin: 0 auto;
      width: 100%;
    }

    /* Seção de Mídia */
    .container-midia {
      flex: 2;
      background: var(--cor-secundaria);
      border-radius: var(--borda-radius);
      box-shadow: var(--sombra);
      overflow: hidden;
      min-height: 500px;
      transition: transform 0.3s ease;
    }

    .container-midia:hover {
      transform: translateY(-2px);
    }

    .container-midia img,
    .container-midia video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: var(--borda-radius);
    }

    /* Lista de Chamados */
    aside {
      flex: 1;
      max-width: 400px;
      background: var(--cor-secundaria);
      border-radius: var(--borda-radius);
      box-shadow: var(--sombra);
      padding: 2rem;
      border-left: 4px solid var(--cor-primaria);
      height: calc(100vh - 220px);
      overflow-y: auto;
    }

    aside h2 {
      color: var(--cor-primaria);
      font-size: 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--cor-primaria-transparente);
    }

    .chamada {
      background: rgba(245, 245, 245, 0.9);
      padding: 1.25rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      border: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .chamada:hover {
      transform: translateX(8px);
      box-shadow: var(--sombra);
      border-color: var(--cor-primaria-transparente);
    }

    .chamada:nth-child(odd) {
      background: rgba(0, 71, 171, 0.03);
    }

    .senha, .paciente-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .fw-bold {
      color: var(--cor-primaria);
      font-weight: 600;
      font-size: 1.1rem;
    }

    .numero-senha, .nome {
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--cor-texto);
    }

    /* Footer Estilizado */
    .footer {
      background: var(--cor-texto);
      color: rgba(255, 255, 255, 0.9);
      padding: 1rem;
      text-align: center;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(4px);
    }

    .footer strong {
      color: var(--cor-primaria);
      font-weight: 500;
    }

    /* Animações */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.02); }
      100% { transform: scale(1); }
    }

    .info-consulta-header.piscando {
      animation: pulse 0.6s ease-in-out;
    }

    /* Responsividade */
    @media (max-width: 1200px) {
      .content-wrapper {
        flex-direction: column;
      }

      .container-midia {
        min-height: 400px;
      }

      aside {
        max-width: 100%;
        height: auto;
      }
    }

    @media (max-width: 768px) {
      #mensagemSenha {
        font-size: 1.8rem;
      }

      .container-midia {
        min-height: 300px;
      }

      .chamada {
        padding: 1rem;
      }
    }

    @media (max-width: 480px) {
      #mensagemSenha {
        font-size: 1.4rem;
      }

      #senhaChamada {
        padding: 0.75rem 1.5rem;
      }
    }
  </style>
</head>

<body>
  <header class="info-consulta-header">
    <div class="container">
      <div id="mensagemSenha">
        <span style="font-size: 1.1rem; font-weight: 400;">Última chamada:</span>
        <strong id="senhaChamada">Carregando...</strong>
      </div>
    </div>
  </header>

  <div class="main-container">
    <div class="content-wrapper">
      <div class="container-midia">
        <?php if(isset($midia_painel) && $midia_painel): ?>
          <?php if($midia_painel['tipo'] == 'imagem'): ?>
            <img src="<?php echo base_url($midia_painel['caminho']); ?>" alt="Mídia">
          <?php elseif($midia_painel['tipo'] == 'video'): ?>
            <video width="100%" height="100%" controls autoplay loop muted playsinline>
              <source src="<?php echo base_url($midia_painel['caminho']); ?>" type="video/mp4">
            </video>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <aside>
        <h2>ÚLTIMOS CHAMADOS</h2>
        <div id="ultimasChamadas">
          <?php if (!empty($ultimasChamadas)): ?>
            <?php foreach ($ultimasChamadas as $index => $chamada): ?>
              <div class="chamada">
                <div class="<?= $chamada['tipo'] === 'senha' ? 'senha' : 'paciente-info' ?>">
                  <span class="fw-bold"><?= strtoupper($chamada['tipo']) ?></span>
                  <span class="<?= $chamada['tipo'] === 'senha' ? 'numero-senha' : 'nome' ?>">
                    <?= $chamada[$chamada['tipo'] === 'senha' ? 'senha' : 'paciente'] ?>
                  </span>
                </div>
                <div class="<?= $chamada['tipo'] === 'senha' ? 'guiche' : 'destino' ?>">
                  <?= $chamada[$chamada['tipo'] === 'senha' ? 'guiche' : 'sala'] ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="chamada">
              <div class="text-center">Nenhuma chamada recente</div>
            </div>
          <?php endif; ?>
        </div>
      </aside>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <span>Powered by <strong>Gees</strong> | ATENDIMENTOS - CLÍNICA MAIS SAÚDE | <?= date('d/m/Y H:i') ?></span>
    </div>
  </footer>

  <script>
    // Scripts JavaScript mantidos conforme necessidade
    let filaAtiva = false;
    const intervaloVerificacao = 10000;

    async function verificarChamada() {
      if (filaAtiva) return;
      filaAtiva = true;

      try {
        const response = await fetch("<?= site_url('chamada2/get_proxima_chamada') ?>");
        const data = await response.json();

        if (data.status === "success") {
          await exibirEfalarChamada(data);
        }
      } catch (error) {
        console.error("Erro na verificação da chamada:", error);
        document.getElementById("senhaChamada").innerText = "Erro ao carregar";
      } finally {
        filaAtiva = false;
      }
    }

    // Restante dos scripts mantido igual
    // ... (manter o restante do JavaScript original)

    document.addEventListener('DOMContentLoaded', () => {
      setInterval(verificarChamada, intervaloVerificacao);
      verificarChamada();
    });
  </script>
</body>

</html>

<?php
function adjustBrightness($hex, $steps, $opacity = 1) {
  $hex = str_replace('#', '', $hex);
  $r = hexdec(substr($hex, 0, 2));
  $g = hexdec(substr($hex, 2, 2));
  $b = hexdec(substr($hex, 4, 2));
  
  $r = max(0, min(255, $r + $steps));
  $g = max(0, min(255, $g + $steps));
  $b = max(0, min(255, $b + $steps));
  
  if ($opacity < 1) {
    return "rgba($r, $g, $b, $opacity)";
  }
  return '#' . sprintf("%02x%02x%02x", $r, $g, $b);
}
?>