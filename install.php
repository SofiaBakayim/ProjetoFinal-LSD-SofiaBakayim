<?php
// Instalador automático do Chronos
echo "<h1>🔧 Instalador do Chronos</h1>";
echo "<p>Configurando o sistema...</p>";

// 1. Verificar conexão à base de dados
echo "<h2>1. Verificação da Base de Dados</h2>";

try {
    $pdo = get_db_connection();
    echo "✅ Conexão à base de dados MySQL bem-sucedida<br>";
    echo "📊 Base de dados: <strong>" . DB_NAME . "</strong><br>";
} catch (Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "<br>";
    exit();
}

// 2. Verificar se as tabelas existem
echo "<h2>2. Verificação das Tabelas</h2>";

$tables = ['users', 'profiles', 'favorites', 'identifications'];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->fetch()) {
            echo "✅ Tabela <strong>$table</strong> existe<br>";
        } else {
            echo "❌ Tabela <strong>$table</strong> não existe<br>";
        }
    } catch (Exception $e) {
        echo "❌ Erro ao verificar tabela $table: " . $e->getMessage() . "<br>";
    }
}

// 3. Criar utilizador de teste
echo "<h2>3. Criação de Utilizador de Teste</h2>";

$test_user = [
    'name' => 'Utilizador Teste',
    'email' => 'teste@chronos.com',
    'password' => '123456'
];

// Verificar se o utilizador já existe
if (!get_user_by_email($test_user['email'])) {
    if (save_user($test_user['name'], $test_user['email'], $test_user['password'])) {
        echo "✅ Utilizador de teste criado<br>";
        echo "📧 Email: {$test_user['email']}<br>";
        echo "🔑 Password: {$test_user['password']}<br>";
    } else {
        echo "❌ Erro ao criar utilizador de teste<br>";
    }
} else {
    echo "📋 Utilizador de teste já existe<br>";
}

// 4. Criar perfil de teste
echo "<h2>4. Criação de Perfil de Teste</h2>";

$test_profile = [
    'name' => 'Sara Correia',
    'bio' => 'Apaixonado por cerâmica ibérica e peças de mercado vintage.',
    'location' => 'Lisboa, Portugal',
    'specialty' => 'ceramica',
    'experience' => 'intermedio',
    'notifications' => ['novos_objetos', 'atualizacoes_valor']
];

if (save_profile($test_user['email'], $test_profile)) {
    echo "✅ Perfil de teste criado<br>";
} else {
    echo "❌ Erro ao criar perfil de teste<br>";
}

// 5. Verificar extensões PHP
$required_extensions = ['session', 'json', 'fileinfo'];
$all_extensions_ok = true;

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extensão: $ext<br>";
    } else {
        echo "❌ Extensão faltando: $ext<br>";
        $all_extensions_ok = false;
    }
}

// 6. Verificar conectividade
if (function_exists('mail')) {
    echo "✅ Função mail disponível<br>";
} else {
    echo "⚠️ Função mail não disponível (email pode não funcionar)<br>";
}

// 7. Criar arquivo .htaccess se não existir
if (!file_exists('.htaccess')) {
    $htaccess_content = "RewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^(.*)$ index.php [QSA,L]";
    file_put_contents('.htaccess', $htaccess_content);
    echo "✅ Arquivo .htaccess criado<br>";
} else {
    echo "📄 Arquivo .htaccess já existe<br>";
}

// 8. Verificar arquivos principais
$main_files = [
    'config.php',
    'login.php',
    'registar.php',
    'perfil.php',
    'logout.php',
    'favorites.php',
    'identifications.php'
];

$all_files_ok = true;
foreach ($main_files as $file) {
    if (file_exists($file)) {
        echo "✅ Arquivo: $file<br>";
    } else {
        echo "❌ Arquivo faltando: $file<br>";
        $all_files_ok = false;
    }
}

// Resumo da instalação
echo "<h2>🎉 Instalação Concluída!</h2>";

if ($all_extensions_ok && $all_files_ok) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>✅ Sistema pronto para uso!</h3>";
    echo "<p>O Chronos foi instalado com sucesso. Pode começar a usar o sistema.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>⚠️ Instalação com problemas</h3>";
    echo "<p>Alguns componentes podem não funcionar corretamente. Verifique os erros acima.</p>";
    echo "</div>";
}

echo "<h3>🔗 Links Úteis:</h3>";
echo "<ul>";
echo "<li><a href='intro.html'>🏠 Página Inicial</a></li>";
echo "<li><a href='login.php'>🔐 Login</a></li>";
echo "<li><a href='registar.php'>📝 Registo</a></li>";
echo "<li><a href='perfil.php'>👤 Perfil</a></li>";
echo "<li><a href='test_system.php'>🧪 Teste do Sistema</a></li>";
echo "</ul>";

echo "<h3>📋 Credenciais de Teste:</h3>";
echo "<p><strong>Email:</strong> teste@chronos.com</p>";
echo "<p><strong>Password:</strong> 123456</p>";

echo "<h3>📚 Documentação:</h3>";
echo "<ul>";
echo "<li><a href='README.md'>📖 README Completo</a></li>";
echo "<li><a href='phpmyadmin_info.php'>📊 Informações da Base de Dados</a></li>";
echo "<li><a href='EMAIL_SETUP.md'>📧 Configuração de Email</a></li>";
echo "<li><a href='GMAIL_SETUP.md'>📧 Configuração Gmail</a></li>";
echo "</ul>";

echo "<h3>🔗 phpMyAdmin:</h3>";
echo "<ul>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>📊 Aceder ao phpMyAdmin</a></li>";
echo "<li><strong>Base de dados:</strong> chronos_db</li>";
echo "<li><strong>Utilizador:</strong> root</li>";
echo "<li><strong>Password:</strong> (em branco)</li>";
echo "</ul>";

echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>💡 Próximos Passos:</h3>";
echo "<ol>";
echo "<li>Teste o login com as credenciais fornecidas</li>";
echo "<li>Explore o perfil e as funcionalidades</li>";
echo "<li>Configure o email se necessário</li>";
echo "<li>Personalize o sistema conforme necessário</li>";
echo "</ol>";
echo "</div>";

echo "<p><em>🎓 Sistema desenvolvido para o projeto final de programação web</em></p>";
?> 