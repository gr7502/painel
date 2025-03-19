<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Chamadas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style_painel.css'); ?>">
</head>

<body class="d-flex flex-column min-vh-100">
<header class="info-consulta-header bg-primary text-white py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div id="mensagemSenha">
                    <span>Última senha chamada:</span> 
                    <strong id="senhaChamada">Carregando...</strong>
                </div>
            </div>
        </div>
    </div>
</header>

  <div class="container-fluid flex-grow-1">
    <main class="row mt-4">
      <div class="col-md-8">
        <div class="video-container ratio ratio-16x9">
          <video controls class="w-100">
            <source src="video.mp4" type="video/mp4">
          </video>
        </div>
      </div>
      <aside class="col-md-4">
        <h2 class="text-center mb-4">ÚLTIMOS CHAMADOS</h2>
        <div class="chamada bg-light p-3 mb-3 rounded">
          <div class="senha">
            <span class="fw-bold">SENHA</span>
            <span class="numero-senha">C-016</span>
          </div>
          <div class="guiche">
            <span>Guichê 02</span>
          </div>
        </div>
        <div class="chamada destaque p-3 mb-3 rounded">
          <div class="senha">
            <span class="fw-bold">PACIENTE</span>
            <span class="senha">Francisca Maria de Oliveira (C-011)</span>
          </div>
          <div class="destino">
            <span>Consultório 06</span>
          </div>
        </div>
        <div class="chamada paciente bg-light p-3 mb-3 rounded">
          <div class="paciente-info">
            <span class="fw-bold">PACIENTE</span>
            <span class="nome">Anderson Marques e Silva (E-021)</span>
          </div>
          <div class="destino">
            <span>Sala de Exames 01</span>
          </div>
        </div>
        <div class="chamada destaque p-3 mb-3 rounded">
          <div class="senha">
            <span class="fw-bold">SENHA</span>
            <span class="numero-senha">E-027</span>
          </div>
          <div class="guiche">
            <span>Guichê 03</span>
          </div>
        </div>
        <div class="chamada  p-3 mb-3 rounded">
          <div class="senha">
            <span class="fw-bold">SENHA</span>
            <span class="numero-senha">C-015</span>
          </div>
          <div class="guiche">
            <span>Guichê 03</span>
          </div>
        </div>
      </aside>
    </main>
  </div>

  <footer class="footer bg-dark text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-4 text-center text-md-start">
          Powered by <strong>Gees</strong>
        </div>
        <div class="col-md-4 text-center">
          <strong>ATENDIMENTOS - CLÍNICA MAIS SAÚDE</strong>
        </div>
        <div class="col-md-4 text-center text-md-end">
          <?php echo date('d \d\e F \d\e Y | H:i'); ?>
        </div>

      </div>
    </div>
  </footer>
  <script>
    let ultimaMensagem = ""; 
    function falarTexto(texto) {
        if ('speechSynthesis' in window) {
            let utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR'; // Define para português
            speechSynthesis.speak(utterance);
        } else {
            console.warn("Seu navegador não suporta Speech Synthesis.");
        }
    }

    function atualizarChamada() {
        fetch("<?php echo base_url('chamada/getUltimaChamada'); ?>")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                let novaMensagem = data.mensagem;
                
                // Atualiza somente se a mensagem for diferente da última
                if (novaMensagem !== ultimaMensagem) {
                    document.getElementById("senhaChamada").innerText = novaMensagem;
                    falarTexto(novaMensagem); // Fala a nova mensagem
                    ultimaMensagem = novaMensagem; // Atualiza a última chamada
                }
            } else {
                document.getElementById("senhaChamada").innerText = "Nenhuma senha chamada";
            }
        })
        .catch(error => console.error("Erro ao atualizar chamada:", error));
    }

    // Atualiza a cada 5 segundos
    setInterval(atualizarChamada, 5000);
    atualizarChamada(); // Chama uma vez ao carregar a página
</script>
</body>

</html>