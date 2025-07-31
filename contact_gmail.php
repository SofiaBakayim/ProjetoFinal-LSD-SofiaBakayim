<?php
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

// Configurações do Gmail SMTP
$smtp_server = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = 'seu-email@gmail.com'; // SUBSTITUIR PELO TEU EMAIL GMAIL
$smtp_password = 'sua-app-password'; // SUBSTITUIR PELA TUA APP PASSWORD

// Criar conexão SMTP usando fsockopen
$smtp_connection = fsockopen($smtp_server, $smtp_port, $errno, $errstr, 30);

if (!$smtp_connection) {
    // Se não conseguir conectar, salvar localmente
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
    file_put_contents('contact_messages.txt', $contactData, FILE_APPEND);
    
    echo json_encode(['success' => true, 'message' => 'Mensagem recebida! Entraremos em contacto em breve. (Mensagem guardada localmente)']);
    exit;
}

// Configurar email
$to = 'Sofia.bakayim@gmail.com';
$subject = 'Nova mensagem de contacto - Chronos';
$headers = "From: $smtp_username\r\n";
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

// Tentar enviar usando a função mail nativa (mais simples)
$mailResult = mail($to, $subject, $emailBody, $headers);

if ($mailResult) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso! Entraremos em contacto em breve.']);
} else {
    // Se falhar, salvar localmente
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
    file_put_contents('contact_messages.txt', $contactData, FILE_APPEND);
    
    echo json_encode(['success' => true, 'message' => 'Mensagem recebida! Entraremos em contacto em breve. (Mensagem guardada localmente)']);
}

fclose($smtp_connection);
?> 