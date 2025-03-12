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
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            /* Apenas para dar um leve contraste */
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
            <h2 class="text-center"><?php echo isset($tipo_senha) ? 'Editar Tipo' : 'Novo Tipo'; ?></h2>

            <form
                action="<?php echo isset($tipo_senha) ? base_url('tiposSenhas/atualizar/' . $tipo_senha->id) : base_url('tiposSenhas/salvar'); ?>"
                method="POST">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                        value="<?php echo isset($tipo_senha) ? $tipo_senha->nome : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="prefixo" class="form-label">Prefixo</label>
                    <input type="text" class="form-control" id="prefixo" name="prefixo"
                        value="<?php echo isset($tipo_senha) ? $tipo_senha->prefixo : ''; ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>
                        <?php echo isset($tipo_senha) ? 'Atualizar' : 'Salvar'; ?></button>
                    <a href="<?php echo base_url('tiposSenhas/index'); ?>" class="btn btn-secondary"><i
                            class="fas fa-arrow-left"></i> Voltar</a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>