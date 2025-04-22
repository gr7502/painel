<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamada de Senhas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <?php
    // Função para ajustar o brilho da cor
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

    // Definindo a cor primária a partir do banco de dados
    $primary_color = isset($config->primary_color) ? $config->primary_color : '#6a11cb'; // Cor padrão
    $secondary_color = adjustBrightness($primary_color, 30); // Cor secundária mais clara
    $accent_color = adjustBrightness($primary_color, 50); // Cor de destaque mais clara
    $danger_color = '#ef4444'; // Cor de perigo fixa
    $text_color = '#2d3748'; // Cor de texto fixa
    $light_bg = '#f8fafc'; // Fundo claro fixo
    ?>

    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --secondary-color: <?php echo $secondary_color; ?>;
            --accent-color: <?php echo $accent_color; ?>;
            --danger-color: <?php echo $danger_color; ?>;
            --text-color: <?php echo $text_color; ?>;
            --light-bg: <?php echo $light_bg; ?>;
        }

        body {
            background: var(--light-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .brand-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
            position: relative;
        }

        .btn-voltar {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-voltar:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .logo-container {
            padding: 15px 0;
        }

        .logo {
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            max-height: 90px;
            width: auto;
        }

        .panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
            transform: scale(1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: none;
            overflow: hidden;
        }

        .panel:hover {
            transform: scale(1.02);
            z-index: 10;
        }

        .panel h3 {
            color: var(--primary-color);
            border-bottom: 2px solid <?php echo adjustBrightness($primary_color, 0, 0.1); ?>;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .btn-chamar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-finalizar {
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.8rem 1.2rem;
            transition: all 0.3s;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-finalizar:hover {
            background: #dc2626;
            transform: scale(1.02);
        }

        .form-select {
            border-radius: 10px;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            font-size: 1.1rem;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px <?php echo adjustBrightness($primary_color, 0, 0.2); ?>;
        }

        .chamada-atual {
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 5px solid var(--primary-color);
            padding: 1.5rem;
            position: relative;
        }

        #filaSenhas {
            display: grid;
            gap: 1rem;
        }

        .senha-item {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            transition: all 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid var(--primary-color);
        }

        .senha-item.chamada-ativa {
            background: <?php echo adjustBrightness($primary_color, 0, 0.05); ?>;
            border-left: 4px solid var(--accent-color);
        }

        .status-indicator {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #cbd5e0;
        }

        .chamada-ativa .status-indicator {
            background: var(--accent-color);
            box-shadow: 0 0 10px <?php echo adjustBrightness($accent_color, 0, 0.4); ?>;
        }

        .text-xlarge {
            font-size: 2rem;
        }

        .feedback-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .logo {
                max-height: 70px !important;
            }
            
            .panel h3 {
                font-size: 1.3rem;
            }
            
            .btn-chamar {
                font-size: 1rem;
                padding: 0.8rem;
            }
            
            .text-xlarge {
                font-size: 1.7rem;
            }
            
            .btn-voltar {
                width: 35px;
                height: 35px;
                left: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Header com Botão Voltar -->
    <div class="brand-header">
        <a href="http://localhost/Painel/" class="btn-voltar" title="Voltar ao Painel">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="container">
            <div class="logo-container text-center">
                <img src="<?php echo base_url('assets/imagens/logo.png'); ?>" class="logo img-fluid" alt="Logo">
            </div>
        </div>
    </div>

    <div class="container py-4">
        <h2 class="mb-5 text-center display-5 fw-bold" style="color: var(--primary-color)">CONTROLE DE ATENDIMENTO</h2>

        <div class="row g-4">
            <!-- Painel Senhas -->
            <div class="col-lg-6">
                <div class="panel p-4">
                    <h3><i class="bi bi-ticket-detailed me-2"></i>CHAMADA DE SENHAS</h3>
                    <div class="mb-4">
                        <label class="form-label fs-5 text-muted">GUICHÊ</label>
                        <select id="guiche" class="form-select">
                            <option>Selecione o Guichê...</option>
                            <option>Guichê 01</option>
                            <option>Guichê 02</option>
                            <option>Guichê 03</option>
                            <option>Guichê 04</option>
                        </select>
                    </div>

                    <button onclick="chamar('senha')" class="btn-chamar mb-4">
                        <i class="bi bi-arrow-down-circle me-2"></i>CHAMAR PRÓXIMA SENHA
                    </button>

                    <div class="chamada-atual">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-2 fs-5">SENHA ATUAL</p>
                                <h4 id="senhaChamada" class="fw-bold mb-0 text-xlarge">---</h4>
                            </div>
                            <span class="badge bg-primary fs-5 py-2"> <span id="guicheChamada">--</span></span>
                        </div>
                        
                        <!-- Botão para finalizar atendimento -->
                        <button id="btnFinalizarSenha" class="btn-finalizar" onclick="finalizarAtendimento()" style="display: none;">
                            <i class="bi bi-x-circle me-2"></i>FINALIZAR ATENDIMENTO
                        </button>
                    </div>
                </div>
            </div>

            <!-- Painel Pacientes -->
            <div class="col-lg-6">
                <div class="panel p-4">
                    <h3><i class="bi bi-person-heart me-2"></i>CHAMADA DE PACIENTES</h3>
                    <div class="mb-4">
                        <label class="form-label fs-5 text-muted">PACIENTE</label>
                        <select id="paciente" class="form-select">
                            <option>Selecione o paciente...</option>
                            <?php foreach ($pacientes as $p): ?>
                                <option value="<?= htmlspecialchars($p->nome) ?>">
                                    <?= htmlspecialchars($p->nome) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-5 text-muted">CONSULTÓRIO</label>
                        <select id="sala" class="form-select">
                            <option>Selecione a sala...</option>
                            <option value="Consultório 01">Consultório 01</option>
                            <option value="Consultório 02">Consultório 02</option>
                            <option value="Consultório 03">Consultório 03</option>
                            <option value="Consultório 04">Consultório 04</option>
                        </select>
                    </div>

                    <button onclick="chamar('paciente')" class="btn-chamar mb-4">
                        <i class="bi bi-megaphone me-2"></i>CHAMAR PACIENTE
                    </button>

                    <div class="chamada-atual">
                        <div>
                            <p class="text-muted mb-2 fs-5">PACIENTE CHAMADO</p>
                            <h4 id="pacienteChamado" class="fw-bold mb-2 text-xlarge">---</h4>
                            <p class="text-muted fs-5" id="salaChamado">---</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fila de Atendimento -->
            <div class="col-12">
                <div class="panel p-4">
                    <h3><i class="bi bi-list-task me-2"></i>FILA DE ATENDIMENTO</h3>
                    <div id="filaSenhas" class="mt-3">
                        <!-- Itens serão adicionados dinamicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let chamadasAtivas = [];
        let senhaAtualId = null;

        function chamar(tipo) {
            let dados = { tipo: tipo };
            const BASE_URL = "<?php echo base_url(); ?>";

            if (tipo === "senha") {
                const guicheField = document.getElementById('guiche');
                const guiche = guicheField ? guicheField.value.trim() : null;
                if (!guiche || guiche === "Selecione o Guichê...") {
                    showFeedback('warning', "Selecione um guichê!");
                    return;
                }
                dados.guiche = guiche;
            } else if (tipo === "paciente") {
                const pacienteField = document.getElementById('paciente');
                const salaField = document.getElementById('sala');
                const paciente = pacienteField ? pacienteField.value.trim() : null;
                const sala = salaField ? salaField.value.trim() : null;
                if (!paciente || paciente === "Selecione o paciente..." || !sala || sala === "Selecione a sala...") {
                    showFeedback('warning', "Selecione um paciente e uma sala!");
                    return;
                }
                dados.paciente = paciente;
                dados.sala = sala;
            }

            fetch(BASE_URL + "chamada2/processar_chamada", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=UTF-8",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(dados)
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro na rede');
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    showFeedback('success', 'Chamada realizada com sucesso!');
                    atualizarListas();
                } else {
                    showFeedback('danger', 'Erro: ' + (data.message || "Falha ao enviar"));
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                showFeedback('danger', 'Erro de comunicação com o servidor');
            });
        }

        function finalizarAtendimento() {
            if (!senhaAtualId || isNaN(senhaAtualId)) {
                showFeedback('warning', 'Nenhuma senha válida em atendimento para finalizar!');
                return;
            }

            if (!confirm("Deseja realmente finalizar este atendimento?")) {
                return;
            }

            const BASE_URL = "<?php echo base_url(); ?>";
            
            const dados = {
                id: senhaAtualId,
                status: 'finalizada',
                motivo: 'atendimento_concluido'
            };

            console.log("Enviando dados para finalização:", dados);

            fetch(BASE_URL + "chamada2/finalizar_atendimento", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=UTF-8",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(dados)
            })
            .then(response => {
                console.log("Status da resposta HTTP:", response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`Erro na rede: ${response.status} ${response.statusText}`);
                }
                return response.json().catch(err => {
                    throw new Error('Resposta não é JSON válido');
                });
            })
            .then(data => {
                console.log("Resposta do servidor:", data);
                if (data.status === "success") {
                    showFeedback('success', 'Atendimento finalizado com sucesso!');
                    senhaAtualId = null;
                    document.getElementById('senhaChamada').textContent = '---';
                    document.getElementById('guicheChamada').textContent = '--';
                    document.getElementById('btnFinalizarSenha').style.display = 'none';
                    atualizarListas();
                } else {
                    showFeedback('danger', 'Erro ao finalizar: ' + (data.message || 'Falha ao atualizar o status'));
                }
            })
            .catch(error => {
                console.error("Erro ao finalizar atendimento:", error);
                showFeedback('danger', 'Erro de comunicação com o servidor: ' + error.message);
            });
        }

        function showFeedback(type, message) {
            const feedback = document.createElement('div');
            feedback.className = `alert alert-${type} feedback-message`;
            feedback.innerHTML = `
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>
                ${message}
            `;
            
            // Remove feedbacks anteriores
            document.querySelectorAll('.feedback-message').forEach(el => el.remove());
            
            document.body.appendChild(feedback);
            
            // Remove após 5 segundos
            setTimeout(() => feedback.remove(), 5000);
        }

        function atualizarListas() {
            fetch("<?php echo base_url('chamada2/ultimas_chamadas'); ?>")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Dados recebidos em atualizarListas:", data); // Log para depuração
                    if (data.status === "success") {
                        // Última senha
                        if (data.ultima_senha && data.ultima_senha.senha_id) {
                            document.getElementById('senhaChamada').textContent = `${data.ultima_senha.senha || '---'}`;
                            document.getElementById('guicheChamada').textContent = `${data.ultima_senha.guiche || '--'}`;
                            senhaAtualId = data.ultima_senha.senha_id; // Usar senha_id em vez de id
                            console.log("Atribuindo senhaAtualId (senha_id):", senhaAtualId); // Log para verificar
                            document.getElementById('btnFinalizarSenha').style.display = 'block';
                        } else {
                            document.getElementById('senhaChamada').textContent = "---";
                            document.getElementById('guicheChamada').textContent = "--";
                            senhaAtualId = null;
                            console.log("Nenhuma senha ativa, senhaAtualId resetado para null");
                            document.getElementById('btnFinalizarSenha').style.display = 'none';
                        }

                        // Último paciente
                        if (data.ultima_paciente) {
                            document.getElementById('pacienteChamado').textContent = data.ultima_paciente.paciente || '---';
                            document.getElementById('salaChamado').textContent = data.ultima_paciente.sala || '---';
                        }

                        // Fila de atendimento
                        if (data.fila_atendimento) {
                            const filaSenhas = document.getElementById('filaSenhas');
                            filaSenhas.innerHTML = '';
                            
                            data.fila_atendimento.forEach(senha => {
                                const item = document.createElement('div');
                                item.className = `senha-item ${senha.senha_id === senhaAtualId ? 'chamada-ativa' : ''}`;
                                item.innerHTML = `
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="status-indicator"></div>
                                        <div>
                                            <div class="fw-bold fs-5">${senha.senha}</div>
                                            <small class="text-muted">Guichê ${senha.guiche}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">${senha.hora}</small>
                                `;
                                filaSenhas.appendChild(item);
                            });
                        }
                    } else {
                        console.error("Erro na resposta de ultimas_chamadas:", data.message || "Sem mensagem");
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar listas:", error);
                    showFeedback('warning', 'Erro ao atualizar a lista de atendimentos');
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            atualizarListas();
            setInterval(atualizarListas, 10000); // Atualiza a cada 10 segundos
        });
    </script>
</body>
</html>