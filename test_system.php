<?php
require_once 'config.php';

echo "<h1>Teste do Sistema Chronos</h1>";

// Teste 1: Verificar se os arquivos existem
echo "<h2>1. Verificação de Arquivos</h2>";
$files = [
    'config.php' => 'Configuração principal',
    'login.php' => 'Sistema de login',
    'registar.php' => 'Sistema de registo',
    'perfil.php' => 'Gestão de perfil',
    'logout.php' => 'Sistema de logout',
    'favorites.php' => 'API de favoritos',
    'identifications.php' => 'API de identificações'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $file - $description<br>";
    } else {
        echo "❌ $file - $description (FALTANDO)<br>";
    }
}

// Teste 2: Verificar permissões
echo "<h2>2. Verificação de Permissões</h2>";
$writable_files = [
    'users.txt',
    'profiles.txt',
    'favorites.txt',
    'identifications.txt'
];

foreach ($writable_files as $file) {
    if (file_exists($file)) {
        if (is_writable($file)) {
            echo "✅ $file - Gravável<br>";
        } else {
            echo "⚠️ $file - Não gravável<br>";
        }
    } else {
        echo "📄 $file - Criado automaticamente<br>";
        file_put_contents($file, '');
    }
}

// Teste 3: Testar funções de utilidade
echo "<h2>3. Teste de Funções</h2>";

// Teste sanitize_input
$test_input = "<script>alert('test')</script>";
$sanitized = sanitize_input($test_input);
echo "Sanitização: " . ($sanitized !== $test_input ? "✅" : "❌") . " Funciona<br>";

// Teste save_user
$test_user = [
    'name' => 'Teste User',
    'email' => 'teste@exemplo.com',
    'password' => '123456'
];

if (save_user($test_user['name'], $test_user['email'], $test_user['password'])) {
    echo "✅ Registo de utilizador funciona<br>";
} else {
    echo "❌ Erro no registo de utilizador<br>";
}

// Teste verify_user
if (verify_user($test_user['email'], $test_user['password'])) {
    echo "✅ Verificação de utilizador funciona<br>";
} else {
    echo "❌ Erro na verificação de utilizador<br>";
}

// Teste 4: Verificar sessões
echo "<h2>4. Teste de Sessões</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Sessões ativas<br>";
} else {
    echo "❌ Sessões não ativas<br>";
}

// Teste 5: Verificar configurações
echo "<h2>5. Configurações</h2>";
echo "SMTP Host: " . (defined('SMTP_HOST') ? SMTP_HOST : 'Não definido') . "<br>";
echo "SMTP Port: " . (defined('SMTP_PORT') ? SMTP_PORT : 'Não definido') . "<br>";

// Teste 6: Verificar base de dados
echo "<h2>6. Base de Dados</h2>";
$db_files = [
    'users.txt' => 'Utilizadores',
    'profiles.txt' => 'Perfis',
    'favorites.txt' => 'Favoritos',
    'identifications.txt' => 'Identificações'
];

foreach ($db_files as $file => $description) {
    if (file_exists($file)) {
        $lines = count(file($file, FILE_IGNORE_NEW_LINES));
        echo "📊 $description: $lines registos<br>";
    } else {
        echo "📊 $description: 0 registos<br>";
    }
}

// Teste 7: Verificar extensões PHP
echo "<h2>7. Extensões PHP</h2>";
$required_extensions = ['session', 'json', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext<br>";
    } else {
        echo "❌ $ext (FALTANDO)<br>";
    }
}

// Teste 8: Verificar conectividade
echo "<h2>8. Conectividade</h2>";
if (function_exists('mail')) {
    echo "✅ Função mail disponível<br>";
} else {
    echo "❌ Função mail não disponível<br>";
}

echo "<h2>Resumo</h2>";
echo "<p>Se todos os testes passaram com ✅, o sistema está pronto para uso!</p>";
echo "<p><a href='intro.html'>Ir para a página inicial</a></p>";
echo "<p><a href='login.php'>Testar login</a></p>";
echo "<p><a href='registar.php'>Testar registo</a></p>";
?> 