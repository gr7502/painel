<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamada de Senhas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/Style_cham.css'); ?>">
    <style>
        .panel {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .col-panel {
            margin-bottom: 20px;
        }

        .btn-chamar {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-chamar:hover {
            background-color: #5a4acd;
        }

        .btn-finalizar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-finalizar:hover {
            background-color: #218838;
        }

        #ultimaChamadaSenha,
        #ultimaChamadaPaciente {
            margin-top: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 5px;
        }

        .senha-item {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .chamada-ativa {
            background: #d4edda !important;
            border: 2px solid #28a745;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo_container text-center mb-4">
            <img src="<?php echo base_url('assets/imagens/logo.png'); ?>" class="logo" alt="Logo">
        </div>
        <h3 class="ms-3 text-center">Painel</h3>
        <!-- Menu de navegação -->
        <!-- ... -->
    </div>

    <div class="container">
        <h2 class="my-4">Chamada de Senhas e Pacientes</h2>

        <div class="row">
            <!-- Painel de Chamada de Senhas Modificado -->
            <div class="col-md-6 col-panel">
                <div class="panel">
                    <h3>Chamada de Senha e Guichê</h3>
                    <div id="formChamadaSenhas">
                        <div class="mb-3">
                            <label for="guiche" class="form-label">Guichê:</label>
                            <select id="guiche" name="guiche" class="form-select" required>
                                <option value="">Selecione o Guichê...</option>
                                <option value="Guiche 1">Guichê 1</option>
                                <option value="Guiche 2">Guichê 2</option>
                                <option value="Guiche 3">Guichê 3</option>
                                <option value="Guiche 4">Guichê 4</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" onclick="chamar('senha')" class="btn-chamar mb-2">
                                <i class="bi bi-arrow-down-circle-fill me-2"></i>Próxima Senha
                            </button>
                        </div>

                        <div id="ultimaChamadaSenha" class="mt-3">
                            <h4>Última Chamada:</h4>
                            <div class="chamada-atual">
                                <p id="senhaChamada" class="fw-bold">Nenhuma senha em atendimento</p>
                                <p id="guicheChamada"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel de Chamada de Pacientes (mantido igual) -->
            <div class="col-md-6 col-panel">
                <div class="panel">
                    <h3>Chamada de Paciente e Consultório</h3>
                    <form id="formChamadaPaciente">
                        <div class="mb-3">
                            <label for="paciente" class="form-label">Paciente:</label>
                            <select id="paciente" name="paciente" class="form-select" required>
                                <option value="">Selecione o paciente...</option>
                                <?php foreach ($pacientes as $p): ?>
                                    <option value="<?= htmlspecialchars($p->nome) ?>" data-id="<?= $p->id ?>">
                                        <?= htmlspecialchars($p->nome) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sala" class="form-label">Consultório/Sala:</label>
                            <select id="sala" name="sala" class="form-select" required>
                                <option value="">Selecione a sala...</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="Consultório <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>">Consultório
                                        <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                                    </option>
                                <?php endfor; ?>
                                <option value="Sala de Exames">Sala de Exames</option>
                                <option value="Sala de Emergência">Sala de Emergência</option>
                            </select>
                        </div>

                        <button type="button" onclick="chamar('paciente')" class="btn-chamar">
                            <i class="bi bi-megaphone-fill me-2"></i>Chamar Paciente
                        </button>

                        <div id="ultimaChamadaPaciente" class="mt-3">
                            <h4>Última Chamada:</h4>
                            <div class="chamada-atual">
                                <p id="pacienteChamado" class="fw-bold">Aguardando...</p>
                                <p id="salaChamado"></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Fila de Senhas Chamadas -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="panel">
                    <h3>Fila de Atendimento</h3>
                    <div id="filaSenhas">
                        <!-- Senhas serão adicionadas aqui dinamicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let chamadasAtivas = [];

    /**
 * Função para enviar a chamada (para senha ou paciente)
 * @param {string} tipo - Pode ser "senha" ou "paciente"
 */
function chamar(tipo) {
    // Coleta os dados conforme o tipo
    let dados = { tipo: tipo };
    const BASE_URL = "<?php echo base_url(); ?>";

    if (tipo === "senha") {
        const guicheField = document.getElementById('guiche');
        const guiche = guicheField ? guicheField.value.trim() : null;
        if (!guiche) {
            alert("Selecione um guichê!");
            return;
        }
        dados.guiche = guiche;
    } else if (tipo === "paciente") {
        const pacienteField = document.getElementById('paciente');
        const salaField = document.getElementById('sala');
        const paciente = pacienteField ? pacienteField.value.trim() : null;
        const sala = salaField ? salaField.value.trim() : null;
        if (!paciente || !sala) {
            alert("Selecione um paciente e uma sala!");
            return;
        }
        dados.paciente = paciente;
        dados.sala = sala;
    }

    // Envia com headers corrigidos
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
            // Atualizações da interface...
        } else {
            alert("Erro: " + (data.message || "Falha ao enviar"));
        }
    })
    .catch(error => {
        console.error("Erro:", error);
        alert("Erro de comunicação com o servidor");
    });
}



        /**
         * Opcional: função para atualizar periodicamente as últimas chamadas na própria view "chamada"
         */
        function atualizarListas() {
            fetch("<?php echo base_url('chamada2/ultimas_chamadas'); ?>")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === "success") {
                        // Atualiza as últimas chamadas de senha
                        if (data.ultima_senha) {
                            document.getElementById('senhaChamada').textContent = data.ultima_senha.senha || 'N/A';
                            document.getElementById('guicheChamada').textContent = data.ultima_senha.guiche || 'N/A';
                        }
                        // Atualiza as últimas chamadas de paciente
                        if (data.ultima_paciente) {
                            document.getElementById('pacienteChamado').textContent = data.ultima_paciente.paciente || 'N/A';
                            document.getElementById('salaChamado').textContent = data.ultima_paciente.consultorio || 'N/A';
                        }
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar listas:", error);
                });
        }

        // Atualiza as listas a cada 30 segundos
        setInterval(atualizarListas, 30000);
        document.addEventListener('DOMContentLoaded', atualizarListas);
    </script>
</body>

</html>