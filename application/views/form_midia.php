<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($midias) ? 'Editar Mídia' : 'Nova Mídia'; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        }

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
            <h2 class="text-center"><?php echo isset($midias) ? 'Editar Mídia' : 'Nova Mídia'; ?></h2>

            <form action="<?php echo base_url('configuracoes/upload_midia'); ?>" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="nome_midia" class="form-label">Nome da Mídia</label>
                    <input type="text" class="form-control" id="nome_midia" name="nome_midia"
                        value="<?php echo isset($midias) ? $midias->nome : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="arquivo_midia" class="form-label">Selecionar Arquivo</label>
                    <input type="file" class="form-control" id="arquivo_midia" name="arquivo_midia" accept="image/*,video/*" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Enviar</button>
                    <a href="<?php echo base_url('painel'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
