<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de senhas</title>
    <!-- Adicione seu CSS ou framework aqui -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>
<!--sidebar-->

<<div class="container">
    <h1 class="mt-4">Tipos de Senhas</h1>
    <?php if (!empty($tipos_senha)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Prefixo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tipos_senha as $tps): ?>
                    <tr>
                        <td><?php echo $tps->nome; ?></td>
                        <td><?php echo $tps->prefixo; ?></td>
                        <td>
                            <a href="<?php echo base_url('tiposSenhas/editar/' . $tps->id); ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                            <a href="<?php echo base_url('tiposSenhas/delete_paciente/' . $tps->id); ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum Tipo encontrado.</p>
    <?php endif; ?>
</div>

<!-- Botão flutuante para adicionar paciente -->
<a href="<?php echo base_url('tiposSenhas/criar'); ?>" class="btn btn-floating">
    <i class="fas fa-plus"></i>
</a>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
