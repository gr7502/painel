<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Senha</title>
    
    <!-- Bootstrap e jQuery UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style_gerar.css'); ?>">
</head>
<body>
    <!-- Header com Logo -->
    <div class="header">
    <a href="<?= base_url('welcome/index'); ?>" ><img src="<?php echo base_url('assets/imagens/logo.png'); ?>" class="logo"alt="Logo"></img></a>
            
    </div>

    <!-- Caixa de Conteúdo -->
    <div class="container d-flex justify-content-center p-4">
        <div class="container-box">
            <h2>Gerar Senha</h2>

            <!-- Botões para os Tipos de Senha -->
            <div class="row mt-4">
                <?php foreach ($tipos as $tipo): ?>
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-primary btn-senha gerar-senha" 
                                data-id="<?php echo $tipo->id; ?>" 
                                data-nome="<?php echo $tipo->nome; ?>" 
                                data-prefixo="<?php echo $tipo->prefixo; ?>">
                            <?php echo $tipo->nome; ?> 
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal jQuery UI -->
    <div id="modalSenha" title="Senha Gerada">
        <p id="senhaTexto"></p>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $(".gerar-senha").click(function () {
                var tipo_id = $(this).data("id");
                var tipo_nome = $(this).data("nome");

                $.ajax({
                    url: "<?php echo base_url('senhas/gerar_senha'); ?>/" + tipo_id,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.error) {
                            alert("Erro: " + response.error);
                        } else {
                            var senhaGerada = response.success;
                            $("#senhaTexto").text("Senha: " + senhaGerada);

                            // Exibe o modal estilizado
                            $("#modalSenha").dialog({
                                modal: true,
                                width: 400,
                                buttons: {
                                    "Imprimir": function () {
                                        imprimirSenha(senhaGerada, tipo_nome);
                                    },
                                    "Fechar": function () {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Erro na requisição AJAX:", xhr.responseText);
                        alert("Erro ao gerar senha. Verifique o console.");
                    }
                });
            });

            // Função para imprimir a senha
            function imprimirSenha(senha, tipo) {
                var printWindow = window.open('', '', 'width=400,height=300');
                printWindow.document.write('<html><head><title>Senha</title></head><body>');
                printWindow.document.write('<h2 style="text-align:center;">' + tipo + '</h2>');
                printWindow.document.write('<h1 style="text-align:center; font-size:50px; color:red;">' + senha + '</h1>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        });
    </script>
</body>
</html>
