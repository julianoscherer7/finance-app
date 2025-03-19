<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$mensagem = '';

// Carregar preferências do usuário
$stmt = $pdo->prepare("SELECT tema, cor_primaria, cor_secundaria FROM user_preferences WHERE usuario_id = ?");
$stmt->execute([$user_id]);
$preferences = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não houver preferências, usar o tema padrão
if (!$preferences) {
    $preferences = [
        'tema' => 'claro',
        'cor_primaria' => '#007bff',
        'cor_secundaria' => '#28a745'
    ];
}

// Aplicar o tema dinamicamente
$tema = $preferences['tema'];
$cor_primaria = $preferences['cor_primaria'];
$cor_secundaria = $preferences['cor_secundaria'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tema = $_POST['tema'];
    $cor_primaria = $_POST['cor_primaria'];
    $cor_secundaria = $_POST['cor_secundaria'];

    // Verificar se já existe uma preferência para o usuário
    $stmt = $pdo->prepare("SELECT id FROM user_preferences WHERE usuario_id = ?");
    $stmt->execute([$user_id]);
    $preference = $stmt->fetch();

    if ($preference) {
        // Atualizar preferência existente
        $stmt = $pdo->prepare("
            UPDATE user_preferences
            SET tema = ?, cor_primaria = ?, cor_secundaria = ?
            WHERE usuario_id = ?
        ");
        $stmt->execute([$tema, $cor_primaria, $cor_secundaria, $user_id]);
    } else {
        // Inserir nova preferência
        $stmt = $pdo->prepare("
            INSERT INTO user_preferences (usuario_id, tema, cor_primaria, cor_secundaria)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $tema, $cor_primaria, $cor_secundaria]);
    }

    $mensagem = '<div class="alert alert-success">Tema salvo com sucesso!</div>';
    // Recarregar as preferências após salvar
    $preferences = ['tema' => $tema, 'cor_primaria' => $cor_primaria, 'cor_secundaria' => $cor_secundaria];
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

        .content {
            flex-grow: 1;
            padding: 40px;
            background: <?php echo ($tema == 'escuro') ? '#444' : '#fff'; ?>;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: <?php echo ($tema == 'escuro') ? '#555' : '#fff'; ?>;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
            color: <?php echo ($tema == 'escuro') ? '#fff' : '#333'; ?>;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
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
        }

        .btn:hover {
            background-color: <?php echo $cor_secundaria; ?>;
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

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Controle Financeiro</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="receitas.php">Receitas</a></li>
            <li><a href="despesas.php">Despesas</a></li>
            <li><a href="categorias.php">Categorias</a></li>
            <li><a href="relatorios.php">Relatórios</a></li>
            <li><a href="configuracoes.php">Configurações</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
    
    <div class="content">
        <div class="card">
            <h1 style="font-size: 24px; margin-bottom: 20px;">Configurações de Tema</h1>
            
            <?php if ($mensagem): ?>
                <?php echo $mensagem; ?>
            <?php endif; ?>
            
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
                
                <button type="submit" class="btn">Salvar Tema</button>
            </form>
        </div>
    </div>
</body>
</html>