<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Chamadas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/style_painel.css'); ?>">
</head>

<body class="d-flex flex-column min-vh-100">
  <header class="info-consulta-header bg-primary text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <div id="mensagemSenha">
            <span>Última chamada:</span> <br>
            <strong id="senhaChamada">Carregando...</strong>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Adicione este container onde você quer que a mídia apareça -->
<div class="container-midia">
    <?php if(isset($midia_painel) && $midia_painel): ?>
        <?php if($midia_painel['tipo'] == 'imagem'): ?>
            <img src="<?php echo base_url($midia_painel['caminho']); ?>" style="max-width: 100%; max-height: 300px;">
        <?php elseif($midia_painel['tipo'] == 'video'): ?>
            <video width="100%" height="300" controls autoplay loop>
                <source src="<?php echo base_url($midia_painel['caminho']); ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
        <?php endif; ?>
    <?php endif; ?>
        </div>
        
      <aside>
        <h2 class="text-center mb-4">ÚLTIMOS CHAMADOS</h2>
        <div id="ultimasChamadas">
          <?php if (!empty($ultimasChamadas)): ?>
            <?php foreach ($ultimasChamadas as $chamada): ?>
              <?php if ($chamada['tipo'] == 'senha'): ?>
                <div class="chamada bg-light p-3 mb-3 rounded">
                  <div class="senha">
                    <span class="fw-bold">SENHA</span>
                    <span class="numero-senha"><?= $chamada['senha'] ?></span>
                  </div>
                  <div class="guiche">
                    <span><?= $chamada['guiche'] ?></span>
                  </div>
                </div>
              <?php elseif ($chamada['tipo'] == 'paciente'): ?>
                <div class="chamada paciente bg-light p-3 mb-3 rounded">
                  <div class="paciente-info">
                    <span class="fw-bold">PACIENTE</span>
                    <span class="nome"><?= $chamada['paciente'] ?></span>
                  </div>
                  <div class="destino">
                    <span><?= $chamada['consultorio'] ?></span>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Nenhuma chamada recente.</p>
          <?php endif; ?>
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
          <?= date('d \d\e F \d\e Y | H:i') ?>
        </div>
      </div>
    </div>
  </footer>

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
        utterance.rate = 1.0; // Velocidade normal
        
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
            .then(response => {
                if (!response.ok) throw new Error("Erro na rede");
                return response.json();
            })
            .then(data => {
                if (data.status === "success" && data.mensagem) {
                    document.getElementById("senhaChamada").innerText = data.mensagem;
                    
                    // Fala apenas se for uma mensagem nova e o sintetizador não estiver ocupado
                    if (data.mensagem !== ultimaMensagemFalada && !sintetizadorOcupado) {
                        setTimeout(() => {
                            falarTexto(data.mensagem);
                            ultimaMensagemFalada = data.mensagem;
                        }, 300); // Pequeno delay para melhor sincronização
                    }
                }
            })
            .catch(error => {
                console.error("Erro ao atualizar chamada:", error);
                document.getElementById("senhaChamada").innerText = "Erro ao carregar chamadas";
            });
    }

    function atualizarChamadas() {
        fetch('<?= site_url('painel/getUltimasChamadasJson') ?>')
            .then(response => {
                if (!response.ok) throw new Error("Erro na rede");
                return response.json();
            })
            .then(data => {
                const container = document.getElementById('ultimasChamadas');
                if (!container) return;
                
                container.innerHTML = '';

                data.forEach((chamada, index) => {
                    const classe = (index % 2 === 0) ? 'chamada-branca' : 'chamada-roxa';
                    const tipoChamada = chamada.tipo === 'senha' ? 'SENHA' : 'PACIENTE';
                    const codigo = chamada.tipo === 'senha' ? chamada.senha : chamada.paciente;
                    const destino = chamada.tipo === 'senha' ? chamada.guiche : chamada.consultorio;

                    container.innerHTML += `
                    <div class="chamada ${classe} p-3 mb-3 rounded">
                        <div class="${chamada.tipo === 'senha' ? 'senha' : 'paciente-info'}">
                            <span class="fw-bold">${tipoChamada}</span>
                            <span class="${chamada.tipo === 'senha' ? 'numero-senha' : 'nome'}">${codigo}</span>
                        </div>
                        <div class="${chamada.tipo === 'senha' ? 'guiche' : 'destino'}">
                            <span>${destino}</span>
                        </div>
                    </div>`;
                });
            })
            .catch(error => {
                console.error('Erro ao buscar chamadas:', error);
                const container = document.getElementById('ultimasChamadas');
                if (container) {
                    container.innerHTML = '<p class="text-danger">Erro ao carregar chamadas</p>';
                }
            });
    }

    // Configura os intervalos
    document.addEventListener('DOMContentLoaded', function() {
        // Atualiza imediatamente ao carregar
        atualizarChamada();
        atualizarChamadas();
        
        // Configura os intervalos regulares
        setInterval(atualizarChamada, 5000); // Atualiza a chamada principal a cada 5s
        setInterval(atualizarChamadas, 10000); // Atualiza a lista a cada 10s (reduzido para menos conflitos)
    });
</script>
</body>

</html>