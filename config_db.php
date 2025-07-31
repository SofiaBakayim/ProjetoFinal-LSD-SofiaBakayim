<?php
// Configurar base de dados - arquivo direto sem router
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar configuração
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'chronos';

    echo "<!DOCTYPE html>";
    echo "<html lang='pt'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Configuração da Base de Dados</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
    echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
    echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
    echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";

    echo "<h1>🔧 Configuração da Base de Dados Chronos</h1>";

    try {
        // Conectar sem especificar base de dados
        $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<div class='success'>✅ Conexão MySQL bem-sucedida</div>";
        
        // Criar base de dados se não existir
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<div class='success'>✅ Base de dados '$dbname' criada/verificada</div>";
        
        // Conectar à base de dados específica
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<div class='success'>✅ Conectado à base de dados '$dbname'</div>";
        
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
                echo "<div class='success'>✅ Tabela '$table_name' criada/verificada</div>";
            } catch (Exception $e) {
                echo "<div class='error'>❌ Erro ao criar tabela '$table_name': " . $e->getMessage() . "</div>";
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
            echo "<div class='success'>✅ Utilizador de teste criado</div>";
            echo "<div class='info'>📧 Email: {$test_user['email']}</div>";
            echo "<div class='info'>🔑 Password: 123456</div>";
        } catch (Exception $e) {
            echo "<div class='info'>📋 Utilizador de teste já existe ou erro: " . $e->getMessage() . "</div>";
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
                echo "<div class='success'>✅ Perfil de teste criado</div>";
            }
        } catch (Exception $e) {
            echo "<div class='info'>📋 Perfil de teste já existe ou erro: " . $e->getMessage() . "</div>";
        }
        
        // Verificar tabelas criadas
        echo "<h2>📊 Tabelas Criadas:</h2>";
        $stmt = $pdo->query("SHOW TABLES");
        $tables_created = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables_created as $table) {
            echo "<div class='success'>✅ $table</div>";
        }
        
        echo "<h2>🎉 Sistema Configurado com Sucesso!</h2>";
        echo "<p>Agora pode:</p>";
        echo "<ul>";
        echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>📊 Ver no phpMyAdmin</a></li>";
        echo "<li><a href='login.php'>🔐 Testar Login</a></li>";
        echo "<li><a href='perfil.php'>👤 Ver Perfil</a></li>";
        echo "</ul>";
        
        echo "<div class='info'>";
        echo "<h3>💡 Próximos Passos:</h3>";
        echo "<ol>";
        echo "<li>Vá ao phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Clique na base de dados 'chronos' no painel esquerdo</li>";
        echo "<li>Veja as tabelas criadas: users, profiles, favorites, identifications</li>";
        echo "<li>Teste o login com: teste@chronos.com / 123456</li>";
        echo "</ol>";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Erro: " . $e->getMessage() . "</div>";
    }

    echo "</body>";
    echo "</html>";
} else {
    // Mostrar formulário
    echo "<!DOCTYPE html>";
    echo "<html lang='pt'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Configuração da Base de Dados</title>";
    echo "<style>";
    echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
    echo ".container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
    echo ".btn { background: #007bff; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }";
    echo ".btn:hover { background: #0056b3; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    
    echo "<div class='container'>";
    echo "<h1>🔧 Configuração da Base de Dados Chronos</h1>";
    echo "<p>Este script vai configurar a base de dados MySQL para o sistema Chronos.</p>";
    echo "<p><strong>O que vai ser criado:</strong></p>";
    echo "<ul>";
    echo "<li>Base de dados 'chronos'</li>";
    echo "<li>Tabelas: users, profiles, favorites, identifications</li>";
    echo "<li>Utilizador de teste: teste@chronos.com / 123456</li>";
    echo "</ul>";
    echo "<form method='POST'>";
    echo "<button type='submit' class='btn'>🚀 Configurar Base de Dados</button>";
    echo "</form>";
    echo "</div>";
    
    echo "</body>";
    echo "</html>";
}
?> 