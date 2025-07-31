<?php
// Temporariamente desativar o router
// header("Location: intro.html");
// exit;

// Permitir acesso direto aos arquivos PHP
$request_uri = $_SERVER['REQUEST_URI'];
$file_path = __DIR__ . parse_url($request_uri, PHP_URL_PATH);

if (file_exists($file_path) && is_file($file_path)) {
    // Se o arquivo existe, servir diretamente
    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
    if ($extension === 'php') {
        include $file_path;
        exit;
    }
}

// Se não for um arquivo PHP específico, redirecionar para intro.html
header("Location: intro.html");
exit;
?> 