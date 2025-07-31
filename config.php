<?php
// Configurações do projeto Chronos
session_start();

// Configurações da base de dados MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'chronos');

// Configurações de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'seu-email@gmail.com');
define('SMTP_PASSWORD', 'sua-senha-app');

// Função para conectar à base de dados
function get_db_connection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}

// Função para criar as tabelas se não existirem
function create_tables() {
    $pdo = get_db_connection();
    
    // Tabela de utilizadores
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    // Tabela de perfis
    $sql_profiles = "CREATE TABLE IF NOT EXISTS profiles (
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
    )";
    
    // Tabela de favoritos
    $sql_favorites = "CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        image VARCHAR(500),
        category VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    // Tabela de identificações
    $sql_identifications = "CREATE TABLE IF NOT EXISTS identifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(100),
        material VARCHAR(255),
        origin VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    try {
        $pdo->exec($sql_users);
        $pdo->exec($sql_profiles);
        $pdo->exec($sql_favorites);
        $pdo->exec($sql_identifications);
        return true;
    } catch (PDOException $e) {
        die("Erro ao criar tabelas: " . $e->getMessage());
    }
}

// Funções utilitárias
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_current_user_email() {
    if (is_logged_in()) {
        return $_SESSION['user_id'];
    }
    return null;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// Funções para gerenciar usuários
function save_user($name, $email, $password) {
    $pdo = get_db_connection();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $hashed_password]);
    } catch (PDOException $e) {
        return false;
    }
}

function verify_user($email, $password) {
    $pdo = get_db_connection();
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

function get_user_by_email($email) {
    $pdo = get_db_connection();
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

function get_user_by_id($id) {
    $pdo = get_db_connection();
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

// Funções para gerenciar perfis
function save_profile($user_email, $profile_data) {
    $pdo = get_db_connection();
    
    try {
        // Primeiro, obter o user_id
        $user = get_user_by_email($user_email);
        if (!$user) return false;
        
        // Verificar se já existe um perfil
        $stmt = $pdo->prepare("SELECT id FROM profiles WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $existing_profile = $stmt->fetch();
        
        if ($existing_profile) {
            // Atualizar perfil existente
            $stmt = $pdo->prepare("UPDATE profiles SET name = ?, bio = ?, location = ?, specialty = ?, experience = ?, notifications = ? WHERE user_id = ?");
            return $stmt->execute([
                $profile_data['name'],
                $profile_data['bio'],
                $profile_data['location'],
                $profile_data['specialty'],
                $profile_data['experience'],
                json_encode($profile_data['notifications']),
                $user['id']
            ]);
        } else {
            // Criar novo perfil
            $stmt = $pdo->prepare("INSERT INTO profiles (user_id, name, bio, location, specialty, experience, notifications) VALUES (?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $user['id'],
                $profile_data['name'],
                $profile_data['bio'],
                $profile_data['location'],
                $profile_data['specialty'],
                $profile_data['experience'],
                json_encode($profile_data['notifications'])
            ]);
        }
    } catch (PDOException $e) {
        return false;
    }
}

function get_profile($user_email) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return null;
        
        $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $profile = $stmt->fetch();
        
        if ($profile) {
            $profile['notifications'] = json_decode($profile['notifications'], true);
            return $profile;
        }
        return null;
    } catch (PDOException $e) {
        return null;
    }
}

// Funções para gerenciar favoritos
function save_favorite($user_email, $item_data) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return false;
        
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, title, image, category) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $user['id'],
            $item_data['title'],
            $item_data['image'],
            $item_data['category']
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function get_favorites($user_email) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return [];
        
        $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user['id']]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function remove_favorite($user_email, $favorite_id) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return false;
        
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ? AND user_id = ?");
        return $stmt->execute([$favorite_id, $user['id']]);
    } catch (PDOException $e) {
        return false;
    }
}

// Funções para gerenciar identificações
function save_identification($user_email, $identification_data) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return false;
        
        $stmt = $pdo->prepare("INSERT INTO identifications (user_id, title, category, material, origin) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $user['id'],
            $identification_data['title'],
            $identification_data['category'],
            $identification_data['material'],
            $identification_data['origin']
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function get_identifications($user_email) {
    $pdo = get_db_connection();
    
    try {
        $user = get_user_by_email($user_email);
        if (!$user) return [];
        
        $stmt = $pdo->prepare("SELECT * FROM identifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user['id']]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// Função para enviar email
function send_email($to, $subject, $message) {
    // Implementação básica - pode ser expandida com PHPMailer
    $headers = "From: chronos@exemplo.com\r\n";
    $headers .= "Reply-To: chronos@exemplo.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Criar base de dados e tabelas automaticamente
function setup_database() {
    try {
        // Conectar sem especificar base de dados
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        
        // Criar base de dados se não existir
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Conectar à base de dados criada
        $pdo = get_db_connection();
        
        // Criar tabelas
        create_tables();
        
        return true;
    } catch (PDOException $e) {
        die("Erro ao configurar base de dados: " . $e->getMessage());
    }
}

// Configurar base de dados automaticamente
setup_database();
?> 