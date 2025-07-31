<?php
require_once __DIR__ . '/SiteController.php';

// Roteamento simples sem SimpleRouter
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/login':
        $controller = new SiteController();
        $controller->login();
        break;
    case '/home':
        $controller = new SiteController();
        $controller->home();
        break;
    default:
        http_response_code(404);
        echo 'Página não encontrada';
        break;
} 