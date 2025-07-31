<?php
require_once 'config.php';

// Verificar se o usuário está logado
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

$user_email = get_current_user_email();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $identification_data = [
            'title' => sanitize_input($_POST['title'] ?? ''),
            'category' => sanitize_input($_POST['category'] ?? ''),
            'material' => sanitize_input($_POST['material'] ?? ''),
            'origin' => sanitize_input($_POST['origin'] ?? ''),
            'date' => date('Y-m-d H:i:s')
        ];
        
        if (save_identification($user_email, $identification_data)) {
            echo json_encode(['success' => true, 'message' => 'Identificação registada com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao registar identificação']);
        }
    } else {
        echo json_encode(['error' => 'Ação inválida']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retornar lista de identificações
    $identifications = get_identifications($user_email);
    echo json_encode(['identifications' => $identifications]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?> 