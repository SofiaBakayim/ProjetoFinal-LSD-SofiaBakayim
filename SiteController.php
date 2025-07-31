<?php
class SiteController {
    public function login() {
        // Renderiza o formulário de login
        include __DIR__ . '/login.php';
    }

    public function home() {
        // Renderiza a página home
        include __DIR__ . '/home.html';
    }
} 