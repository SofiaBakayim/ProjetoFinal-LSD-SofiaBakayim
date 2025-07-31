<?php
// Verificar se é uma requisição AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        exit;
    }
    
    // Obter dados do formulário
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validação básica
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
        exit;
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email inválido']);
        exit;
    }
    
    // Salvar mensagem num arquivo
    $timestamp = date('Y-m-d H:i:s');
    $contactData = "
=== NOVA MENSAGEM DE CONTACTO ===
Data: $timestamp
Nome: $name
Email: $email
Mensagem:
$message
=====================================

";
    
    // Salvar no arquivo
    file_put_contents('contact_messages.txt', $contactData, FILE_APPEND);
    
    // Tentar enviar email
    $to = 'Sofia.bakayim@gmail.com';
    $subject = 'Nova mensagem de contacto - Chronos';
    $headers = "From: chronos@localhost\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    $emailBody = "
Nova Mensagem de Contacto - Chronos
=====================================

Nome: $name
Email: $email
Mensagem:
$message

---
Enviado através do formulário de contacto do site Chronos
";
    
    $mailResult = mail($to, $subject, $emailBody, $headers);
    
    if ($mailResult) {
        echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso! Entraremos em contacto em breve.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Mensagem recebida! Entraremos em contacto em breve. (Mensagem guardada localmente)']);
    }
    exit;
}

// Se não for AJAX, redirecionar para a página principal
header("Location: help.html");
exit;
?> 