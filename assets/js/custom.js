function atualizarPainel() {
    $.ajax({
        url: "<?= base_url('index.php/painel/get_senha_chamada'); ?>",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.senha) {
                $("#mensagemSenha").html("Última senha chamada: <strong>" + response.senha + "</strong>");
                falarSenha(response.senha);
            }
        }
    });
}

// Função para falar a senha
function falarSenha(senha) {
    let synth = window.speechSynthesis;
    let utterance = new SpeechSynthesisUtterance("Senha " + senha);
    utterance.lang = "pt-BR";
    synth.speak(utterance);
}

// Atualiza a tela a cada 5 segundos
setInterval(atualizarPainel, 5000);
