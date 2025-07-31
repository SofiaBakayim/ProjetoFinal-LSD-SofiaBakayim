<?php
// Teste simples da base de dados
echo "<h1>🔧 Teste da Base de Dados Chronos</h1>";

// Configurações
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'chronos';

try {
    // Conectar sem especificar base de dados
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexão MySQL bem-sucedida<br>";
    
    // Criar base de dados se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de dados '$dbname' criada/verificada<br>";
    
    // Conectar à base de dados específica
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado à base de dados '$dbname'<br>";
    
    // Criar tabelas
    $tables = [
        'users' => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        'profiles' => "CREATE TABLE IF NOT EXISTS profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            bio TEXT,
            location VARCHAR(255),
            specialty VARCHAR(100),
            experience VARCHAR(50),
            notifications JSON,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        'favorites' => "CREATE TABLE IF NOT EXISTS favorites (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            image VARCHAR(500),
            category VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        'identifications' => "CREATE TABLE IF NOT EXISTS identifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            category VARCHAR(100),
            material VARCHAR(255),
            origin VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];
    
    foreach ($tables as $table_name => $sql) {
        try {
            $pdo->exec($sql);
            echo "✅ Tabela '$table_name' criada/verificada<br>";
        } catch (Exception $e) {
            echo "❌ Erro ao criar tabela '$table_name': " . $e->getMessage() . "<br>";
        }
    }
    
    // Criar utilizador de teste
    $test_user = [
        'name' => 'Utilizador Teste',
        'email' => 'teste@chronos.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT)
    ];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$test_user['name'], $test_user['email'], $test_user['password']]);
        echo "✅ Utilizador de teste criado<br>";
        echo "📧 Email: {$test_user['email']}<br>";
        echo "🔑 Password: 123456<br>";
    } catch (Exception $e) {
        echo "📋 Utilizador de teste já existe ou erro: " . $e->getMessage() . "<br>";
    }
    
    // Criar perfil de teste
    $test_profile = [
        'name' => 'Sara Correia',
        'bio' => 'Apaixonado por cerâmica ibérica e peças de mercado vintage.',
        'location' => 'Lisboa, Portugal',
        'specialty' => 'ceramica',
        'experience' => 'intermedio',
        'notifications' => json_encode(['novos_objetos', 'atualizacoes_valor'])
    ];
    
    try {
        // Obter user_id
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(['teste@chronos.com']);
        $user = $stmt->fetch();
        
        if ($user) {
            $stmt = $pdo->prepare("INSERT INTO profiles (user_id, name, bio, location, specialty, experience, notifications) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user['id'],
                $test_profile['name'],
                $test_profile['bio'],
                $test_profile['location'],
                $test_profile['specialty'],
                $test_profile['experience'],
                $test_profile['notifications']
            ]);
            echo "✅ Perfil de teste criado<br>";
        }
    } catch (Exception $e) {
        echo "📋 Perfil de teste já existe ou erro: " . $e->getMessage() . "<br>";
    }
    
    // Verificar tabelas criadas
    echo "<h2>📊 Tabelas Criadas:</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables_created = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables_created as $table) {
        echo "✅ $table<br>";
    }
    
    echo "<h2>🎉 Sistema Configurado com Sucesso!</h2>";
    echo "<p>Agora pode:</p>";
    echo "<ul>";
    echo "<li><a href='http://localhost/phpmyadmin'>📊 Ver no phpMyAdmin</a></li>";
    echo "<li><a href='login.php'>🔐 Testar Login</a></li>";
    echo "<li><a href='perfil.php'>👤 Ver Perfil</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?> 