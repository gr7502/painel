<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamadas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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
            right: 10px;
            font-size: 1.5vw;
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
                                <span
                                    class="numero"><?= $chamada['tipo'] === 'senha' ? $chamada['senha'] : $chamada['paciente'] ?></span>
                            </div>
                            <div class="destino">
                                <span><?= $chamada['tipo'] === 'senha' ? $chamada['guiche'] : $chamada['consultorio'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma chamada recente.</p>
                <?php endif; ?>
               
            </div>
            <div id="relogio" class="relogio"></div>
        </div>
    </div>

    <script>
        let ultimaMensagemFalada = "";
        let sintetizadorOcupado = false;

        function falarTexto(texto) {
            if (!('speechSynthesis' in window)) {
                console.warn("Seu navegador não suporta Speech Synthesis.");
                return;
            }

            // Cancela qualquer fala pendente
            window.speechSynthesis.cancel();

            let utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR';
            utterance.rate = 1.0;

            utterance.onstart = () => {
                sintetizadorOcupado = true;
            };

            utterance.onend = utterance.onerror = () => {
                sintetizadorOcupado = false;
            };

            window.speechSynthesis.speak(utterance);
        }

        function atualizarChamada() {
            fetch("<?php echo base_url('chamada/getUltimaChamada'); ?>")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success" && data.mensagem) {
                        document.getElementById("senhaChamada").innerText = data.mensagem;

                        if (data.mensagem !== ultimaMensagemFalada && !sintetizadorOcupado) {
                            setTimeout(() => {
                                falarTexto(data.mensagem);
                                ultimaMensagemFalada = data.mensagem;
                            }, 500);
                        }
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar chamada:", error);
                    document.getElementById("senhaChamada").innerText = "Erro ao carregar chamadas";
                });
        }

        function atualizarChamadas() {
            fetch("<?php echo site_url('painel/getUltimasChamadasJson'); ?>")
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('ultimos-chamados');
                    if (!container) return;

                    container.innerHTML = '';

                    data.forEach((chamada, index) => {
                        const tipoChamada = chamada.tipo === 'senha' ? 'SENHA' : 'PACIENTE';
                        const codigo = chamada.tipo === 'senha' ? chamada.senha : chamada.paciente;
                        const destino = chamada.tipo === 'senha' ? chamada.guiche : chamada.consultorio;

                        container.innerHTML += `
                    <div class="chamado-card">
                        <div class="info">
                            <span class="fw-bold">${tipoChamada}</span>
                            <span class="numero">${codigo}</span>
                        </div>
                        <div class="destino">
                            <span>${destino}</span>
                        </div>
                    </div>`;
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar chamadas:', error);
                    const container = document.getElementById('ultimos-chamados');
                    if (container) {
                        container.innerHTML = '<p class="text-danger">Erro ao carregar chamadas</p>';
                    }
                });
        }

        // Configura os intervalos
        document.addEventListener('DOMContentLoaded', function () {
            atualizarChamada();
            atualizarChamadas();

            setInterval(atualizarChamada, 5000);
            setInterval(atualizarChamadas, 5000);
        });
    </script>
    <script>
    function atualizarRelogio() {
    const agora = new Date();

    // Formatando a data
    const opcoesData = { 
        weekday: 'long', 
        day: '2-digit', 
        month: 'long', 
        year: 'numeric' 
    };
    const dataFormatada = agora.toLocaleDateString('pt-BR', opcoesData);

    // Formatando a hora
    const horaFormatada = agora.toLocaleTimeString('pt-BR', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });

    // Atualizando o elemento do relógio
    document.getElementById('relogio').innerHTML = `${dataFormatada} - ${horaFormatada}`;
}

// Atualiza o relógio a cada segundo
setInterval(atualizarRelogio, 1000);
atualizarRelogio();

</script>
</body>

</html>