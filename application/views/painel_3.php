<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamadas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }
        .chamada {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3%;
            font-size: 4vw;
            font-weight: bold;
            background: #6a5acd;
            color: white;
            border-radius: 10px;
            height: 20%;
            margin-bottom: 2%;
        }
        .linha {
            height: 4px;
            background: #6a5acd;
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
            background: #6a5acd;
            color: white;
            font-size: 2.5vw;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="painel">
        <div class="chamada">
            <div>Senha: <span id="senhaAtual">RECEPÇÃO0034</span></div>
            <div>Destino: <span id="guicheAtual">Guichê 4</span></div>
        </div>
        <div class="linha"></div>
        <div class="ultimos-chamados">
            <h3>Últimos chamados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Senha</th>
                        <th>Destino</th>
                    </tr>
                </thead>
                <tbody id="listaChamadas">
                    <tr><td>RECEPÇÃO0034</td><td>Guichê 4</td></tr>
                    <tr><td>RECEPÇÃO0032</td><td>Guichê 3</td></tr>
                    <tr><td>RECEPÇÃO0030</td><td>Guichê 2</td></tr>
                    <tr><td>PÓS ATENDIMENTO0005</td><td>Guichê 1</td></tr>
                    <tr><td>RECEPÇÃO0029</td><td>Guichê 5</td></tr>
                    <tr><td>PÓS ATENDIMENTO0004</td><td>Guichê 1</td></tr>
                    <tr><td>RECEPÇÃO0027</td><td>Guichê 3</td></tr>
                    <tr><td>RECEPÇÃO0025</td><td>Guichê 2</td></tr>
                </tbody>
            </table>
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
                    if (data.status === "success") {
                        const tipoChamada = data.tipo;
                        const senha = data.senha;
                        const guiche = data.guiche;
                        const paciente = data.paciente;
                        const consultorio = data.consultorio;

                        if (tipoChamada === 'senha') {
                            document.getElementById("chamada").innerHTML = `
                                <span>Senha: ${senha}</span>
                                <span>Guichê: ${guiche}</span>
                            `;
                        } else if (tipoChamada === 'paciente') {
                            document.getElementById("chamada").innerHTML = `
                                <span>Paciente: ${paciente}</span>
                                <span>Consultório: ${consultorio}</span>
                            `;
                        }

                        if (data.status === "success" && data.mensagem) {
                            document.getElementById("senhaChamada").innerText = data.mensagem;

                            if (data.mensagem !== ultimaMensagemFalada && !sintetizadorOcupado) {
                                setTimeout(() => {
                                    falarTexto(data.mensagem);
                                    ultimaMensagemFalada = data.mensagem;
                                }, 500);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar chamada:", error);
                    document.getElementById("chamada").innerText = "Erro ao carregar chamadas";
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
   
</body>
</html>