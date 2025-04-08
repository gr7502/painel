// Função para atualizar o relógio na tela
function atualizarRelogio() {
    const agora = new Date();

    // Formata a data
    const opcoesData = {
      weekday: 'long',
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    };
    const dataFormatada = agora.toLocaleDateString('pt-BR', opcoesData);

    // Formata a hora
    const horaFormatada = agora.toLocaleTimeString('pt-BR', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });

    // Atualiza o elemento do relógio
    document.getElementById('relogio').innerHTML = `${dataFormatada} - ${horaFormatada}`;
  }

  // Atualiza o relógio a cada segundo
  setInterval(atualizarRelogio, 1000);
  atualizarRelogio();