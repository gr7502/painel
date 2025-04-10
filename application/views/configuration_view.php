<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações do Painel - Gees HealthTech</title>
    <style>
        :root {
            --primary-color: <?php echo isset($config->primary_color) ? $config->primary_color : '#6a5acd'; ?>;
            --primary-light: <?php echo isset($config->primary_color) ? adjustBrightness($config->primary_color, 40) : '#9370db'; ?>;
            --primary-dark: <?php echo isset($config->primary_color) ? adjustBrightness($config->primary_color, -40) : '#483d8b'; ?>;
            --background: #f5f6fa;
            --card-bg: #ffffff;
            --text-color: #2d2d2d;
            --accent-color: #e6e6fa;
            --border-radius: 20px;
            --box-shadow: 0 10px 30px rgba(106, 90, 205, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            line-height: 1.8;
            padding: 50px 20px;
            font-size: 18px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            align-items: center;
            margin-bottom: 50px;
            padding: 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: var(--border-radius);
            color: white;
            box-shadow: var(--box-shadow);
        }

        .logo {
            margin-right: 25px;
            display: flex;
            align-items: center;
        }

        .logo img {
            max-width: 180px;
            height: auto;
            transition: var(--transition);
        }

        .logo img:hover {
            transform: scale(1.2); 
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
        }

        /* Restante do CSS permanece igual */
        .card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 50px;
            margin-bottom: 50px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(106, 90, 205, 0.2);
        }

        .success-message {
            color: #28a745;
            background-color: #e8f5e9;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 20px;
        }

        .error-message {
            color: #dc3545;
            background-color: #ffe6e6;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 40px;
        }

        label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 22px;
        }

        input[type="text"],
        input[type="color"],
        input[type="file"] {
            width: 100%;
            padding: 18px;
            border: 2px solid var(--accent-color);
            border-radius: 12px;
            font-size: 20px;
            transition: var(--transition);
        }

        input[type="text"]:focus,
        input[type="color"]:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 10px rgba(106, 90, 205, 0.3);
        }

        input[type="color"] {
            height: 70px;
            width: 120px;
            padding: 5px;
            cursor: pointer;
            border: none;
            background: none;
        }

        .tab-container {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            border-bottom: none;
        }

        .tab {
            padding: 15px 30px;
            cursor: pointer;
            background-color: var(--accent-color);
            color: var(--primary-color);
            border-radius: 12px 12px 0 0;
            font-weight: 500;
            font-size: 20px;
            transition: var(--transition);
        }

        .tab.active {
            background-color: var(--primary-color);
            color: white;
        }

        .tab:hover:not(.active) {
            background-color: var(--primary-light);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .upload-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-upload-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 20px;
        }

        .file-upload-btn:hover {
            background-color: var(--primary-dark);
        }

        .file-name {
            font-size: 18px;
            color: #666;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 12px;
            font-size: 22px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: block;
            margin-top: 30px;
        }

        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .preview-section {
            margin-top: 50px;
            padding-top: 40px;
            border-top: 1px solid var(--accent-color);
        }

        .preview-section h2 {
            font-size: 26px;
            color: var(--primary-color);
            margin-bottom: 25px;
        }

        .preview-img {
            max-width: 100%;
            max-height: 450px;
            border-radius: 16px;
            box-shadow: var(--box-shadow);
            object-fit: cover;
            transition: var(--transition);
        }

        .preview-img:hover {
            transform: scale(1.02);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <img src="<?php echo base_url('assets/imagens/Senha.png'); ?>" alt="Logo Gees HealthTech">
            </div>
            <h1>Configurações do Painel</h1>
        </header>
        
        <div class="card">
            <?php if ($this->input->get('success')): ?>
                <div class="success-message">Configurações salvas com sucesso!</div>
            <?php endif; ?>
            <?php if ($this->input->get('error')): ?>
                <div class="error-message">Erro ao salvar configurações: <?php echo urldecode($this->input->get('error')); ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo site_url('configuration/update'); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="primary_color">Cor Primária</label>
                    <input type="color" name="primary_color" id="primary_color" 
                           value="<?php echo isset($config->primary_color) ? $config->primary_color : '#6a5acd'; ?>">
                </div>
                
                <div class="tab-container">
                    <div class="tab active" data-tab="url">Usar URL</div>
                    <div class="tab" data-tab="upload">Upload de Arquivo</div>
                </div>
                
                <div class="tab-content active" id="url-tab">
                    <div class="form-group">
                        <label for="image_url">URL da Imagem</label>
                        <input type="text" name="image_url" id="image_url" 
                               value="<?php echo isset($config->image_url) ? $config->image_url : ''; ?>" 
                               placeholder="https://exemplo.com/imagem.jpg">
                    </div>
                </div>
                
                <div class="tab-content" id="upload-tab">
                    <div class="form-group">
                        <label>Upload de Imagem</label>
                        <div class="upload-container">
                            <div class="file-upload">
                                <span class="file-upload-btn">Selecionar Arquivo</span>
                                <input type="file" name="image_file" id="image_file" accept="image/*">
                            </div>
                            <span class="file-name" id="file-name">Nenhum arquivo selecionado</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit">Salvar Configurações</button>
            </form>
            
            <?php if(isset($config->image_url) && !empty($config->image_url)): ?>
                <div class="preview-section">
                    <h2>Prévia da Imagem</h2>
                    <img src="<?php echo $config->image_url; ?>" alt="Imagem do Painel" class="preview-img" id="image-preview">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                const tabId = tab.getAttribute('data-tab') + '-tab';
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        const fileInput = document.getElementById('image_file');
        const fileName = document.getElementById('file-name');
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileName.textContent = e.target.files[0].name;
                const preview = document.getElementById('image-preview');
                if (preview) {
                    const file = e.target.files[0];
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                fileName.textContent = 'Nenhum arquivo selecionado';
            }
        });
        
        const urlInput = document.getElementById('image_url');
        urlInput.addEventListener('input', (e) => {
            const preview = document.getElementById('image-preview');
            if (preview && e.target.value) {
                preview.src = e.target.value;
            }
        });
    </script>
</body>
</html>

<?php
function adjustBrightness($hex, $steps) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    return '#' . sprintf("%02x%02x%02x", $r, $g, $b);
}
?>