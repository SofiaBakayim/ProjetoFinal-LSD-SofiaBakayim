<?php
// Exportar base de dados para SQL
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações
$host = '127.0.0.1';
$user = 'root';
$pass = '123456789Abc';
$dbname = 'chronos';

echo "<!DOCTYPE html>";
echo "<html lang='pt'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Exportar Base de Dados - Chronos</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }";
echo ".code { background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; margin: 10px 0; overflow-x: auto; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>📤 Exportar Base de Dados - Sistema Chronos</h1>";

try {
    // Conectar à base de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='success'>✅ Conectado à base de dados '$dbname'</div>";
    
    // Criar arquivo SQL
    $sql_content = "-- =====================================================\n";
    $sql_content .= "-- Sistema Chronos - Base de Dados\n";
    $sql_content .= "-- Exportado em: " . date('Y-m-d H:i:s') . "\n";
    $sql_content .= "-- =====================================================\n\n";
    
    // Criar base de dados
    $sql_content .= "-- Criar base de dados\n";
    $sql_content .= "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    $sql_content .= "USE `$dbname`;\n\n";
    
    // Obter estrutura das tabelas
    $tables = ['users', 'profiles', 'favorites', 'identifications'];
    
    foreach ($tables as $table) {
        // Obter CREATE TABLE
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $create_table = $stmt->fetch(PDO::FETCH_ASSOC);
        $sql_content .= "-- Estrutura da tabela `$table`\n";
        $sql_content .= $create_table['Create Table'] . ";\n\n";
        
        // Obter dados da tabela
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $sql_content .= "-- Dados da tabela `$table`\n";
            foreach ($rows as $row) {
                $columns = array_keys($row);
                $values = array_values($row);
                
                // Escapar valores
                $escaped_values = array_map(function($value) use ($pdo) {
                    if ($value === null) {
                        return 'NULL';
                    }
                    return $pdo->quote($value);
                }, $values);
                
                $sql_content .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escaped_values) . ");\n";
            }
            $sql_content .= "\n";
        }
    }
    
    // Salvar arquivo SQL
    $filename = 'chronos_database.sql';
    file_put_contents($filename, $sql_content);
    
    echo "<div class='success'>✅ Arquivo SQL criado: $filename</div>";
    echo "<div class='info'>📁 Localização: " . realpath($filename) . "</div>";
    
    // Mostrar estatísticas
    echo "<h2>📊 Estatísticas da Exportação:</h2>";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<div class='info'>📋 Tabela '$table': $count registos</div>";
    }
    
    // Mostrar conteúdo do arquivo
    echo "<h2>📄 Conteúdo do Arquivo SQL:</h2>";
    echo "<div class='code'>";
    echo htmlspecialchars(substr($sql_content, 0, 2000)) . "...";
    echo "</div>";
    
    echo "<h2>🎉 Exportação Concluída!</h2>";
    echo "<p>Agora pode:</p>";
    echo "<ul>";
    echo "<li><a href='$filename' download class='btn'>📥 Baixar Arquivo SQL</a></li>";
    echo "<li><a href='http://localhost/phpmyadmin' target='_blank' class='btn'>📊 Ver no phpMyAdmin</a></li>";
    echo "<li><a href='info_professor.html' class='btn'>📋 Informações para o Professor</a></li>";
    echo "</ul>";
    
    echo "<div class='info'>";
    echo "<h3>💡 Para o GitHub:</h3>";
    echo "<ol>";
    echo "<li>Adicione o arquivo '$filename' ao seu repositório</li>";
    echo "<li>Inclua um README.md com instruções de instalação</li>";
    echo "<li>Adicione todos os arquivos PHP e HTML</li>";
    echo "<li>Inclua capturas de ecrã do sistema funcionando</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Erro: " . $e->getMessage() . "</div>";
}

echo "</body>";
echo "</html>";
?> 