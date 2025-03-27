<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Carrega preferências do banco de dados
$user_id = $_SESSION['id'];
$stmt = $GLOBALS['pdo']->prepare("SELECT tema, cor_primaria, cor_secundaria FROM user_preferences WHERE usuario_id = ?");
$stmt->execute([$user_id]);
$preferences = $stmt->fetch(PDO::FETCH_ASSOC);

// Define padrões se não existir configuração
if (!$preferences) {
    $preferences = [
        'tema' => 'claro',
        'cor_primaria' => '#007bff',
        'cor_secundaria' => '#28a745'
    ];
    
    // Insere os padrões no banco
    $stmt = $GLOBALS['pdo']->prepare("INSERT INTO user_preferences (usuario_id, tema, cor_primaria, cor_secundaria) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $preferences['tema'], $preferences['cor_primaria'], $preferences['cor_secundaria']]);
}

// Define variáveis globais de tema
$GLOBALS['tema'] = $preferences['tema'];
$GLOBALS['cor_primaria'] = $preferences['cor_primaria'];
$GLOBALS['cor_secundaria'] = $preferences['cor_secundaria'];
?>