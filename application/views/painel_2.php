<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Chamadas</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --cor-primaria: <?php echo isset($config->primary_color) ? $config->primary_color : '#6a5acd'; ?>;
      --cor-primaria-transparente: <?php echo isset($config->primary_color) ? adjustBrightness($config->primary_color, 0, 0.9) : 'rgba(106, 90, 205, 0.9)'; ?>;
    }

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
      font-family: 'Poppins', sans-serif;
    }

    .container {
      display: flex;
      width: 100%;
      height: 100%;
      background: white;
      position: relative;
    }

    .chamada {
        flex: 2;
        background: var(--cor-primaria);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3vw;
        font-weight: bold;
        text-align: center;
        padding: 20px;
        max-width: 72%;
    }

    .chamada span {
      font-size: 4vw;
    }

    @keyframes piscar {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .chamada.piscando {
        animation: piscar 0.5s ease-in-out 3;
    }

    .logo-container {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      text-align: center;
      width: 100%;
    }

    .logo-container img {
      max-width: 250px;
      height: auto;
    }

    .config-image {
      position: absolute;
      top: 10px;
      left: 10px;
      max-width: 150px;
      height: auto;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .relogio {
      position: absolute;
      bottom: 10px;
      right: 8px;
      font-size: 1.2vw;
      font-weight: bold;
      color: white;
      background: var(--cor-primaria-transparente);
      padding: 15px;
      border-radius: 10px;
      text-align: center;
    }

    .ultimos-chamados {
        flex: 1;
        background: white;
        padding: 20px;
        overflow-y: auto;
        min-width: 200px;
    }

    .titulo {
      text-align: center;
      font-size: 2vw;
      font-weight: bold;
      margin-bottom: 15px;
      color: var(--cor-primaria);
    }

    .chamado-card {
      border-left: 5px solid var(--cor-primaria);
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
    <!-- Imagem configurada no topo à esquerda -->
    <?php if (isset($config->image_url) && !empty($config->image_url)): ?>
      <img src="<?php echo $config->image_url; ?>" alt="Imagem Configurada" class="config-image">
    <?php endif; ?>

    <!-- Logo movida para a parte inferior -->
    <div class="logo-container">
      <img src="<?php echo base_url('assets/imagens/Senha.png'); ?>" alt="Logo Gees HealthTech">
    </div>

    <div class="chamada">
      <div id="mensagemSenha">
        <span>Última chamada:</span> <br>
        <strong id="senhaChamada">Aguardando...</strong>
      </div>
    </div>

    <div id="ultimos-chamados" class="ultimos-chamados">
      <h2 class="titulo">Últimos Chamados</h2>
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
              <span><?= $chamada['tipo'] === 'senha' ? $chamada['guiche'] : $chamada['sala'] ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div id="sem-chamadas" class="sem-chamadas">
          <p class="fw-bold">Nenhuma chamada recente.</p>
        </div>
      <?php endif;?>
    </div>

    <div class="relogio-container">
      <div id="relogio" class="relogio"></div>
    </div>
  </div>

  <script src="<?php echo base_url('assets/js/relogio.js'); ?>"></script>
  <script>
    // Conecta ao servidor WebSocket
    const ws = new WebSocket('ws://localhost:8080');

    ws.onopen = function () {
        console.log('Conectado ao servidor WebSocket');
    };

    ws.onmessage = async function (event) {
        const data = JSON.parse(event.data);

        if (data.type === 'new_call') {
            const call = data.call;
            await exibirEfalarChamada({
                mensagem: call.mensagem,
                tipo: call.tipo,
                dados: {
                    senha: call.tipo === 'senha' ? call.number : null,
                    paciente: call.tipo === 'paciente' ? call.number : null,
                    guiche: call.guiche,
                    sala: call.sala
                },
                fila_id: call.fila_id
            });
        } else if (data.type === 'queue_update') {
            atualizarListaChamados(data.queue);
        }
    };

    ws.onclose = function () {
        console.log('Desconectado do servidor WebSocket');
    };

    ws.onerror = function (error) {
        console.error('Erro no WebSocket:', error);
    };

    async function exibirEfalarChamada(data) {
        // Atualiza o texto da última chamada na interface
        document.getElementById("senhaChamada").innerText = data.mensagem;
        atualizarUltimosChamados(data);

        try {
            // Toca o som de alerta antes de falar a mensagem
            await tocarSomAlerta();
            // Fala a mensagem após o som
            await falarTexto(data.mensagem);
            // Marca a chamada como falada
            if (data.fila_id) {
                await marcarComoFalada(data.fila_id);
            }
        } catch (error) {
            console.error("Erro no fluxo de chamada:", error);
        }
    }

    function atualizarUltimosChamados(data) {
        const container = document.getElementById('ultimos-chamados');
        const semChamadas = document.getElementById('sem-chamadas');

        // Remove a mensagem "sem chamadas" se ela existir
        if (semChamadas) {
            semChamadas.remove();
        }

        // Adiciona o novo chamado à lista
        const novoChamado = document.createElement('div');
        novoChamado.className = 'chamado-card';
        novoChamado.innerHTML = `
            <div class="info">
                <span class="fw-bold">${data.tipo.toUpperCase()}</span>
                <span class="numero">${data.dados.senha || data.dados.paciente}</span>
            </div>
            <div class="destino">
                <span>${data.dados.guiche || data.dados.sala}</span>
            </div>
        `;
        container.insertBefore(novoChamado, container.firstChild);
    }

    function atualizarListaChamados(queue) {
        const container = document.getElementById('ultimos-chamados');
        container.innerHTML = '<h2 class="titulo">Últimos Chamados</h2>';

        if (queue.length === 0) {
            const semChamadas = document.createElement('div');
            semChamadas.id = 'sem-chamadas';
            semChamadas.className = 'sem-chamadas';
            semChamadas.innerHTML = '<p class="fw-bold">Nenhuma chamada recente.</p>';
            container.appendChild(semChamadas);
        } else {
            queue.forEach(call => {
                const chamado = document.createElement('div');
                chamado.className = 'chamado-card';
                chamado.innerHTML = `
                    <div class="info">
                        <span class="fw-bold">${call.tipo.toUpperCase()}</span>
                        <span class="numero">${call.tipo === 'senha' ? call.number : call.number}</span>
                    </div>
                    <div class="destino">
                        <span>${call.guiche || call.sala}</span>
                    </div>
                `;
                container.appendChild(chamado);
            });
        }
    }

    async function marcarComoFalada(filaId) {
        try {
            await fetch(`<?= site_url('chamada2/marcar_como_falada/') ?>${filaId}`);
        } catch (error) {
            console.error("Erro ao marcar como falada:", error);
        }
    }

    function tocarSomAlerta() {
        return new Promise((resolve) => {
            const audio = new Audio("<?= base_url('assets/sounds/alert.mp3') ?>");
            audio.volume = 0.8;

            const chamadaElement = document.querySelector('.chamada');
            chamadaElement.classList.add('piscando');

            audio.onended = resolve;
            audio.onerror = (err) => {
                console.error("Erro ao tocar o som de alerta:", err);
                resolve();
            };
            audio.play();
        });
    }

    function falarTexto(texto) {
        return new Promise((resolve) => {
            if (!window.speechSynthesis) {
                console.warn("Síntese de voz não suportada");
                return resolve();
            }

            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR';
            utterance.rate = 1.0;
            utterance.pitch = 1.0;

            utterance.onend = resolve;
            utterance.onerror = (err) => {
                console.error("Erro na fala:", err);
                resolve();
            };

            window.speechSynthesis.speak(utterance);
        });
    }
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