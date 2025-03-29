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
</head>

<body>
    <!--Sidebar-->
    <div class="sidebar">
        <div class="logo_container text-center mb-4">
            <img src="<?php echo base_url('assets/imagens/logo.png'); ?>" class="logo" alt="Logo">
        </div>
        <h3 class="ms-3 text-center">Painel</h3>

        <!-- Botão Home -->
        <a href="<?= base_url('welcome/index'); ?>" class="btn btn-home w-100 text-start px-3">
            <i class="bi bi-house-door-fill me-2"></i> Home
        </a>

        <!-- Menu de Pacientes -->
        <a href="#" data-bs-toggle="collapse" data-bs-target="#menuPacientes"
            class="collapse-menu d-block text-start px-3">
            <i class="bi bi-file-person-fill me-2"></i> Pacientes <i class="fas fa-chevron-down float-end"></i>
        </a>
        <div class="collapse" id="menuPacientes">
            <a href="<?= base_url('paciente/index'); ?>" class="d-block ms-4"><i class="bi bi-person-circle me-2"></i>
                Todos os pacientes</a>
            <a href="<?= base_url('paciente/create_paciente'); ?>" class="d-block ms-4"><i
                    class="bi bi-person-fill-add me-2"></i> Adicionar pacientes</a>
        </div>

        <!-- Menu Senhas -->
        <a href="#" data-bs-toggle="collapse" data-bs-target="#menuSenhas"
            class="collapse-menu d-block text-start px-3">
            <i class="bi bi-file-binary-fill me-2"></i> Senhas <i class="fas fa-chevron-down float-end"></i>
        </a>
        <div class="collapse" id="menuSenhas">
            <a href="<?= base_url('tiposSenhas/index'); ?>" class="d-block ms-4"><i
                    class="bi bi-file-earmark-binary-fill me-2"></i> Tipos de senhas</a>
            <a href="<?= base_url('tiposSenhas/criar'); ?>" class="d-block ms-4"><i
                    class="bi bi-file-earmark-diff-fill me-2"></i> Adicionar senhas</a>
            <a href="<?= base_url('senhas/gerar'); ?>" class="d-block ms-4"><i class="bi bi-key-fill me-2"></i> Gerar
                Senhas</a>
        </div>

        <!-- Menu Painel -->
        <a href="#" data-bs-toggle="collapse" data-bs-target="#menuPainel"
            class="collapse-menu d-block text-start px-3">
            <i class="bi bi-card-heading"> </i> Painel <i class="fas fa-chevron-down float-end"></i>
        </a>
        <div class="collapse" id="menuPainel">
            <a href="<?= base_url('chamada/index'); ?>" class="d-block ms-4"><i class="bi bi-layers-fill"></i> Chamar
                senha</a>
            <a href="<?= base_url('painel/index'); ?>" class="d-block ms-4"><i class="bi bi-dash-square-fill"></i>
                Painel</a>
        </div>

        <!-- Menu Configurações -->
        <a href="#" data-bs-toggle="collapse" data-bs-target="#menuConfiguracoes"
            class="collapse-menu d-block text-start px-3">
            <i class="bi bi-card-heading"> </i> Painel <i class="fas fa-chevron-down float-end"></i>
        </a>
        <div class="collapse" id="menuConfiguracoes">
            <a href="<?= base_url('chamada/index'); ?>" class="d-block ms-4"><i class="bi bi-layers-fill"></i> Chamar
                senha</a>
            <a href="<?= base_url('painel/index'); ?>" class="d-block ms-4"><i class="bi bi-dash-square-fill"></i>
                Painel</a>
        </div>
    </div>

    <div class="container">
        <h2>Chamada de Senhas e Pacientes</h2>

        <div class="row">
            <!-- Painel de Chamada de Senhas -->
            <div class="col-md-6 col-panel">
                <div class="panel">
                    <h3>Chamada de Senha e Guichê</h3>

                    <form id="formChamadaSenhas">
                        <div class="mb-3">
                            <label for="guiche" class="form-label">Guichê:</label>
                            <select id="guiche" name="guiche" class="form-select">
                                <option value="">Selecione o Guichê...</option>
                                <option value="Guichê 01">Guichê 01</option>
                                <option value="Guichê 02">Guichê 02</option>
                                <option value="Guichê 03">Guichê 03</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <select id="senha" name="senha" class="form-select">
                                <option value="">Selecione a senha...</option>
                                <option value="CN - 01">CN -01</option>
                                <?php foreach ($senhas as $s): ?>
                                    <option value="<?= htmlspecialchars($s->senha) ?>"><?= htmlspecialchars($s->senha) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="button" onclick="chamar('senha')">Chamar Senha</button>
                        <div id="ultimaChamadaSenha">
                            <h4>Última Chamada:</h4>
                            <p id="senhaChamada">Aguardando...</p>
                            <p id="guicheChamada"></p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Painel de Chamada de Pacientes -->
            <div class="col-md-6 col-panel">
                <div class="panel">
                    <h3>Chamada de Paciente e Consultório</h3>

                    <form id="formChamadaPaciente">
                        <div class="mb-3">
                            <label for="paciente" class="form-label">Paciente:</label>
                            <select id="paciente" name="paciente" class="form-select">
                                <option value="">Selecione o paciente...</option>
                                <?php foreach ($pacientes as $p): ?>
                                    <option value="<?= htmlspecialchars($p->nome) ?>"><?= htmlspecialchars($p->nome) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sala" class="form-label">Consultório/Sala:</label>
                            <select id="sala" name="sala" class="form-select">
                                <option value="">Selecione a sala...</option>
                                <option value="Consultório 01">Consultório 01</option>
                                <option value="Consultório 02">Consultório 02</option>
                                <option value="Sala de Exames">Sala de Exames</option>
                            </select>
                        </div>

                        <button type="button" onclick="chamar('paciente')">Chamar Senha</button>


                        <div id="ultimaChamadaPaciente">
                            <h4>Última Chamada:</h4>
                            <p id="pacienteChamado">Aguardando...</p>
                            <p id="salaChamado"></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>

        function chamar(tipo) {
            let senha = document.getElementById('senha').value;
            let guiche = document.getElementById('guiche').value;
            let paciente = document.getElementById('paciente').value;
            let sala = document.getElementById('sala').value;

            if (tipo === "senha" && (!guiche || !senha)) {
                alert("Selecione um guichê e uma senha!");
                return;
            }

            if (tipo === "paciente" && (!paciente || !sala)) {
                alert("Selecione um paciente e uma sala!");
                return;
            }

            fetch("<?php echo base_url('chamada/chamar'); ?>", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    tipo: tipo,
                    senha: senha,
                    guiche: guiche,
                    paciente: paciente,
                    sala: sala
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Chamada enviada para o painel!");
                        atualizarChamada();
                    } else {
                        alert("Erro ao chamar senha: " + data.mensagem);
                    }
                })
                .catch(error => console.error("Erro:", error));
        }


    </script>
</body>

</html>