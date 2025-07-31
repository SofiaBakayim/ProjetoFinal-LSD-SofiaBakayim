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

// Configurar email com headers mais simples
$to = 'Sofia.bakayim@gmail.com';
$subject = 'Nova mensagem de contacto - Chronos';
$headers = "From: chronos@localhost\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Criar corpo do email simples
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

// Tentar enviar email
$mailResult = mail($to, $subject, $emailBody, $headers);

if ($mailResult) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso! Entraremos em contacto em breve.']);
} else {
    // Se falhar, vamos salvar num arquivo como backup
    $backupData = date('Y-m-d H:i:s') . " - Nome: $name, Email: $email, Mensagem: $message\n";
    file_put_contents('contact_backup.txt', $backupData, FILE_APPEND);
    
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar email. A mensagem foi guardada e será processada em breve.']);
}
?> 