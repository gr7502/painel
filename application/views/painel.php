<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style_painel.css'); ?>">
</head>

<body>
    <div class="header">ATENDIMENTOS - CLINICA CUIDAR MAIS</div>
    <div class="main-content">
        <div class="video-section">
            <video width="100%" height="100%" controls>
                <source src="<?php echo base_url('assets/videos/1.mp4'); ?>" type="video/mp4">
                Seu navegador não suporta vídeos.
            </video>
        </div>
        <div class="info-section">
    <h2 id="senha-atual">Aguardando chamada...</h2>
    <p>Paciente: <span id="paciente"><strong>-</strong></span></p>
    <p>Consulta: <span id="consulta"><strong>-</strong></span></p>
    <p>Consultório: <span id="consultorio"><strong>-</strong></span></p>
</div>

        <div class="history-section">
            <h4>ÚLTIMOS CHAMADOS</h4>
            <div class="history-item">C-015 - Guichê 03</div>
            <div class="history-item">E-027 - Guichê 03</div>
            <div class="history-item">Francisca Maria - Consultório 06</div>
        </div>
    </div>
    
<script>
function chamar(tipo) {
    fetch(`<?php echo base_url('chamada/chamar'); ?>?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                document.getElementById("chamada").innerText = data.erro;
            } else {
                document.getElementById("chamada").innerText = data.mensagem;

                // Fala a mensagem chamada
                const synth = window.speechSynthesis;
                const utterance = new SpeechSynthesisUtterance(data.mensagem);
                synth.speak(utterance);
            }
        })
        .catch(error => console.error("Erro ao chamar:", error));
}


// Atualiza o painel a cada 5 segundos
function atualizarPainel() {
    $.getJSON('<?= base_url("chamada/get_chamadas") ?>', function(data) {
        console.log("atualizando"); 
        if (data.senha_atual) {
            let senhaAtual = data.senha_atual.senha; 
            let guicheAtual = data.senha_atual.guiche || 'Guichê 1';
            

            $('#senha-atual').text(senhaAtual);

            // Evita repetir a chamada
            let senhaAnterior = localStorage.getItem("ultimaSenha");
            if (senhaAtual !== senhaAnterior) {
                localStorage.setItem("ultimaSenha", senhaAtual);
                chamarSenha(senhaAtual, guicheAtual);
            }
        }

        let historicoHTML = '';
        if (Array.isArray(data.historico)) {
            data.historico.forEach(chamada => {
                historicoHTML += `<div class="historico-item">
                    <strong>Senha:</strong> ${chamada.senha}
                </div>`;
            });
        }

        $('#historico-chamadas').html(historicoHTML);
    });

}

// Atualiza automaticamente
setInterval(atualizarPainel, 5000);

$(document).ready(function () {
    let ultimaSenha = localStorage.getItem("ultimaSenha");
    console.log(ultimaSenha)
    if (ultimaSenha) {
        chamarSenha(ultimaSenha, 'Guichê 1');
    }
});

function atualizarSenha() {
    fetch("<?php echo base_url('senhas/senha_chamada'); ?>")
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                document.getElementById("senha-atual").innerText = "Aguardando chamada...";
            } else {
                document.getElementById("senha-atual").innerText = `${data.senha}`;
                document.getElementById("paciente").innerHTML = `<strong>${data.paciente}</strong>`;
                document.getElementById("consulta").innerHTML = `<strong>${data.medico}</strong>`;
                document.getElementById("consultorio").innerHTML = `<strong>${data.consultorio}</strong>`;
                
                // Fala a senha chamada
                const synth = window.speechSynthesis;
                const utterance = new SpeechSynthesisUtterance(`Senha ${data.senha}, dirija-se ao guichê ${data.guiche}`);
                synth.speak(utterance);
            }
        })
        .catch(error => console.error("Erro ao buscar senha:", error));
}

// Atualiza a cada 5 segundos
setInterval(atualizarSenha, 5000);

</script>



</body>

</html>