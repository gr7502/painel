<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamadas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cor-primaria: #6a5acd;
            --cor-primaria-transparente: rgba(106, 90, 205, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .painel {
            width: 95%;
            height: 95%;
            margin: auto;
            padding: 2%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chamada {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3%;
            font-size: 4vw;
            font-weight: bold;
            background: var(--cor-primaria);
            color: white;
            border-radius: 10px;
            height: 20%;
            margin-bottom: 2%;
        }

        @keyframes piscar {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .chamada.piscando {
            animation: piscar 0.5s ease-in-out 3;
            transform: translateZ(0);
        }

        .linha {
            height: 4px;
            background: var(--cor-primaria);
            margin: 2% 0;
        }

        .ultimos-chamados {
            font-size: 2vw;
            height: 70%;
            display: flex;
            flex-direction: column;
        }

        .ultimos-chamados h3 {
            font-size: 3vw;
            margin-bottom: 2%;
            color: var(--cor-primaria);
        }

        table {
            width: 100%;
            height: 90%;
            border-collapse: collapse;
            font-size: 2vw;
        }

        th, td {
            padding: 1.5%;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: var(--cor-primaria);
            color: white;
            font-size: 2.5vw;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e8e8e8;
        }

        .logo-container {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
        }

        .config-media {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 200px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            object-fit: contain;
        }

        .config-media video {
            width: 200px;
            height: auto;
        }

        .relogio {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 1.5vw;
            font-weight: bold;
            color: white;
            background: var(--cor-primaria-transparente);
            padding: 8px 12px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="painel">
        <!-- Configurable Media -->
        <?php if (isset($config->image_url) && !empty($config->image_url)): ?>
            <?php
                $ext = strtolower(pathinfo($config->image_url, PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'webm']);
            ?>
            <?php if ($isVideo): ?>
                <video src="<?php echo htmlspecialchars($config->image_url); ?>" class="config-media" autoplay loop muted playsinline>
                    <source src="<?php echo htmlspecialchars($config->image_url); ?>" type="video/<?php echo $ext; ?>">
                    Seu navegador não suporta vídeos.
                </video>
            <?php else: ?>
                <img src="<?php echo htmlspecialchars($config->image_url); ?>" alt="Imagem Configurada" class="config-media">
            <?php endif; ?>
        <?php endif; ?>

        <!-- Current Call -->
        <div class="chamada" id="chamada">
            <div>Senha: <span id="senhaAtual">Carregando...</span></div>
            <div>Destino: <span id="guicheAtual">Carregando...</span></div>
        </div>

        <div class="linha"></div>

        <!-- Last Calls -->
        <div class="ultimos-chamados">
            <h3>Últimos chamados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Senha</th>
                        <th>Destino</th>
                    </tr>
                </thead>
                <tbody id="listaChamadas"></tbody>
            </table>
        </div>

        <!-- Logo -->
        <div class="logo-container">
            <img src="<?php echo base_url('assets/imagens/Senha.png'); ?>" alt="Logo">
        </div>

        <!-- Clock -->
        <div class="relogio-container">
            <div id="relogio" class="relogio"></div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/relogio.js'); ?>"></script>
    <script>
        let filaAtiva = false;
        let ultimaMensagemFalada = "";
        const intervaloVerificacao = 5000;

        async function verificarFila() {
            if (filaAtiva) return;
            filaAtiva = true;

            try {
                const response = await fetch("<?php echo site_url('chamada2/get_proxima_chamada'); ?>");
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                if (data.status === "success" && data.mensagem !== ultimaMensagemFalada) {
                    await exibirEfalarChamada(data);
                    ultimaMensagemFalada = data.mensagem;
                }
            } catch (error) {
                console.error("Erro na verificação da fila:", error);
                document.getElementById("senhaAtual").innerText = "Erro";
                document.getElementById("guicheAtual").innerText = "Erro";
            } finally {
                filaAtiva = false;
            }
        }

        async function exibirEfalarChamada(data) {
            const chamadaElement = document.getElementById("chamada");
            const senhaAtualElement = document.getElementById("senhaAtual");
            const guicheAtualElement = document.getElementById("guicheAtual");

            // Update call display
            if (data.tipo === 'senha') {
                senhaAtualElement.innerText = data.senha;
                guicheAtualElement.innerText = data.guiche;
            } else if (data.tipo === 'paciente') {
                senhaAtualElement.innerText = data.paciente;
                guicheAtualElement.innerText = data.consultorio;
            }

            // Blink animation
            chamadaElement.classList.add('piscando');

            try {
                // Play alert sound
                await tocarSomAlerta();
                // Speak message
                await falarTexto(data.mensagem);
                // Mark as spoken
                if (data.fila_id) {
                    await marcarComoFalada(data.fila_id);
                }
            } catch (error) {
                console.error("Erro no fluxo de chamada:", error);
            } finally {
                setTimeout(() => {
                    chamadaElement.classList.remove('piscando');
                }, 1500);
                await atualizarUltimosChamados();
            }
        }

        async function atualizarUltimosChamados() {
            try {
                const response = await fetch("<?php echo site_url('chamada2/get_ultimas_chamadas'); ?>");
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                const listaChamadas = document.getElementById('listaChamadas');
                listaChamadas.innerHTML = '';

                if (data.status === "success" && data.chamadas && data.chamadas.length > 0) {
                    const ultimosSete = data.chamadas.slice(0, 7);
                    ultimosSete.forEach(chamada => {
                        const codigo = chamada.tipo === 'senha' ? chamada.senha : chamada.paciente;
                        const destino = chamada.tipo === 'senha' ? chamada.guiche : chamada.consultorio;
                        listaChamadas.innerHTML += `
                            <tr>
                                <td>${codigo}</td>
                                <td>${destino}</td>
                            </tr>`;
                    });
                } else {
                    listaChamadas.innerHTML = `
                        <tr>
                            <td colspan="2">Nenhuma chamada recente</td>
                        </tr>`;
                }
            } catch (error) {
                console.error("Erro ao atualizar últimos chamados:", error);
                document.getElementById('listaChamadas').innerHTML = `
                    <tr>
                        <td colspan="2">Erro ao carregar chamadas</td>
                    </tr>`;
            }
        }

        async function marcarComoFalada(filaId) {
            try {
                await fetch(`<?php echo site_url('chamada2/marcar_como_falada/'); ?>${filaId}`);
            } catch (error) {
                console.error("Erro ao marcar como falada:", error);
            }
        }

        function tocarSomAlerta() {
            return new Promise((resolve) => {
                const audio = new Audio("<?php echo base_url('assets/sounds/alert.mp3'); ?>");
                audio.volume = 0.8;
                audio.onended = resolve;
                audio.onerror = (err) => {
                    console.error("Erro ao tocar som:", err);
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

                window.speechSynthesis.cancel();
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

        document.addEventListener('DOMContentLoaded', () => {
            verificarFila();
            atualizarUltimosChamados();
            setInterval(verificarFila, intervaloVerificacao);
            setInterval(atualizarUltimosChamados, intervaloVerificacao);
        });
    </script>
</body>
</html>