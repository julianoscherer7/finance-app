<?php
require 'theme.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tema = $_POST['tema'];
    $cor_primaria = $_POST['cor_primaria'];
    $cor_secundaria = $_POST['cor_secundaria'];

    // Atualiza no banco
    $stmt = $GLOBALS['pdo']->prepare("UPDATE user_preferences SET tema = ?, cor_primaria = ?, cor_secundaria = ? WHERE usuario_id = ?");
    $stmt->execute([$tema, $cor_primaria, $cor_secundaria, $user_id]);
    
    // Atualiza as variáveis globais imediatamente
    $GLOBALS['tema'] = $tema;
    $GLOBALS['cor_primaria'] = $cor_primaria;
    $GLOBALS['cor_secundaria'] = $cor_secundaria;
    
    $_SESSION['mensagem'] = 'Configurações salvas com sucesso!';
    header("Location: configuracoes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Controle Financeiro</title>
    <style>
        /* Estilos dinâmicos com base no tema */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            background-color: <?php echo ($tema == 'escuro') ? '#333' : '#f5f5f5'; ?>;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
        }

        .sidebar {
            width: 250px;
            background: <?php echo ($tema == 'escuro') ? '#222' : '#333'; ?>;
            color: #fff;
            padding: 20px;
            height: 100vh;
        }

        .sidebar h2 {
            margin-top: 0;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            font-size: 16px;
            padding: 8px 0;
        }

        .sidebar ul li a:hover {
            color: #ccc;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
            background: <?php echo ($tema == 'escuro') ? '#444' : '#fff'; ?>;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
        }

        .card {
            background: <?php echo ($tema == 'escuro') ? '#555' : '#fff'; ?>;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: <?php echo ($tema == 'escuro') ? '#666' : '#fff'; ?>;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: <?php echo $cor_primaria; ?>;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 16px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
        }

        input[type="color"] {
            width: 60px;
            height: 40px;
            padding: 2px;
            border-radius: 4px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
    </style>
    <style>
    body {
        background-color: <?php echo ($GLOBALS['tema'] == 'escuro') ? '#333' : '#f5f5f5'; ?>;
        color: <?php echo ($GLOBALS['tema'] == 'escuro') ? '#fff' : '#333'; ?>;
    }
    
    .sidebar {
        background: <?php echo ($GLOBALS['tema'] == 'escuro') ? '#222' : '#333'; ?>;
    }
    
    .content {
        background: <?php echo ($GLOBALS['tema'] == 'escuro') ? '#444' : '#fff'; ?>;
    }
    
    .btn-primary {
        background-color: <?php echo $GLOBALS['cor_primaria']; ?>;
    }
    
    .btn-secondary {
        background-color: <?php echo $GLOBALS['cor_secundaria']; ?>;
    }
</style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="content">
        <div class="card">
            <h1>Configurações de Tema</h1>
            
            <?php if (isset($mensagem)) echo $mensagem; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="tema">Tema</label>
                    <select id="tema" name="tema" class="form-control" required>
                        <option value="claro" <?php echo ($tema == 'claro') ? 'selected' : ''; ?>>Claro</option>
                        <option value="escuro" <?php echo ($tema == 'escuro') ? 'selected' : ''; ?>>Escuro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="cor_primaria">Cor Primária</label>
                    <input type="color" id="cor_primaria" name="cor_primaria" value="<?php echo $cor_primaria; ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="cor_secundaria">Cor Secundária</label>
                    <input type="color" id="cor_secundaria" name="cor_secundaria" value="<?php echo $cor_secundaria; ?>" class="form-control" required>
                </div>
                
                <button type="submit" class="btn">Salvar Configurações</button>
            </form>
        </div>
    </div>
</body>
</html>