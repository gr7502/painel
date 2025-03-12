<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($paciente) ? 'Editar Paciente' : 'Novo Paciente'; ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <style>
        html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa; /* Apenas para dar um leve contraste */
        }
        /* Centraliza horizontalmente e verticalmente */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container-form {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-form">
        <h2 class="text-center"><?php echo isset($paciente) ? 'Editar Paciente' : 'Novo Paciente'; ?></h2>
        <form action="<?php echo isset($paciente) ? base_url('paciente/atualizar/' . $paciente->id) : base_url('paciente/store'); ?>" method="POST">
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo isset($paciente) ? $paciente->nome : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="nascimento" class="form-label">Data de Nascimento</label>
                <input type="text" class="form-control" id="nascimento" name="nascimento" value="<?php echo isset($paciente) ? date('d/m/Y', strtotime($paciente->nascimento)) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo isset($paciente) ? $paciente->cpf : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($paciente) ? $paciente->email : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <textarea class="form-control" id="endereco" name="endereco" rows="3"><?php echo isset($paciente) ? $paciente->endereco : ''; ?></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> <?php echo isset($paciente) ? 'Atualizar' : 'Salvar'; ?></button>
                <a href="<?php echo base_url('paciente/index'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
            </div>
            
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#cpf').mask('000.000.000-00');
        $('#nascimento').mask('00/00/0000');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
