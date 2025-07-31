<?php
header('Content-Type: application/json');

// Ativar logging para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Log dos dados recebidos
error_log("Contact form data - Name: $name, Email: $email, Message: $message");

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

// Configurar email
$to = 'Sofia.bakayim@gmail.com'; // Email da Sofia
$subject = 'Nova mensagem de contacto - Chronos';
$headers = "From: noreply@chronos.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// Criar corpo do email
$emailBody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #630102; color: #FFD97D; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #630102; }
        .value { margin-top: 5px; padding: 10px; background: white; border-left: 3px solid #FFD97D; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Nova Mensagem de Contacto - Chronos</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Nome:</div>
                <div class='value'>" . htmlspecialchars($name) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>" . htmlspecialchars($email) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Mensagem:</div>
                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
            </div>
        </div>
    </div>
</body>
</html>
";

// Log da tentativa de envio
error_log("Attempting to send email to: $to");

// Tentar enviar email
$mailResult = mail($to, $subject, $emailBody, $headers);

// Log do resultado
error_log("Mail result: " . ($mailResult ? 'SUCCESS' : 'FAILED'));

if ($mailResult) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso! Entraremos em contacto em breve.']);
} else {
    // Verificar se a função mail está disponível
    if (!function_exists('mail')) {
        echo json_encode(['success' => false, 'message' => 'Função de email não disponível no servidor.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem. Tente novamente mais tarde.']);
    }
}
?> 