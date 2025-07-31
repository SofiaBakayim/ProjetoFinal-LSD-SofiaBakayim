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
        $item_data = [
            'title' => sanitize_input($_POST['title'] ?? ''),
            'image' => sanitize_input($_POST['image'] ?? ''),
            'category' => sanitize_input($_POST['category'] ?? ''),
            'date' => date('Y-m-d H:i:s')
        ];
        
        if (save_favorite($user_email, $item_data)) {
            echo json_encode(['success' => true, 'message' => 'Favorito adicionado com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao adicionar favorito']);
        }
    } elseif ($action === 'remove') {
        $favorite_id = (int)($_POST['favorite_id'] ?? 0);
        
        if (remove_favorite($user_email, $favorite_id)) {
            echo json_encode(['success' => true, 'message' => 'Favorito removido com sucesso!']);
        } else {
            echo json_encode(['error' => 'Erro ao remover favorito']);
        }
    } else {
        echo json_encode(['error' => 'Ação inválida']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retornar lista de favoritos
    $favorites = get_favorites($user_email);
    echo json_encode(['favorites' => $favorites]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?> 