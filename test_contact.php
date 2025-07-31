<?php
header('Content-Type: application/json');

// Teste simples
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Teste';
    $email = $_POST['email'] ?? 'teste@teste.com';
    $message = $_POST['message'] ?? 'Mensagem de teste';
    
    // Salvar no arquivo
    $data = date('Y-m-d H:i:s') . " - $name, $email, $message\n";
    file_put_contents('test_messages.txt', $data, FILE_APPEND);
    
    echo json_encode(['success' => true, 'message' => 'Teste funcionou! Mensagem guardada.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?> 