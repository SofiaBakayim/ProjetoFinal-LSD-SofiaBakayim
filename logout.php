<?php
require_once 'config.php';

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial
redirect('intro.html');
?> 