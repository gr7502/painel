<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!--sidebar-->
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
    </div>
    <div class="content_home">
        <h2>Bem-vindo ao sistema!</h2>
        <p>Escolha uma opção no menu lateral.</p>
    </div>
</body>

</html>