<?php
require_once 'config.php';

echo "<h1>📊 Informações da Base de Dados Chronos</h1>";

// Verificar conexão
try {
    $pdo = get_db_connection();
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "✅ <strong>Conexão à base de dados bem-sucedida!</strong><br>";
    echo "Base de dados: <strong>" . DB_NAME . "</strong><br>";
    echo "Host: <strong>" . DB_HOST . "</strong><br>";
    echo "Utilizador: <strong>" . DB_USER . "</strong>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "❌ <strong>Erro de conexão:</strong> " . $e->getMessage();
    echo "</div>";
    exit();
}

// Mostrar estatísticas das tabelas
echo "<h2>📈 Estatísticas das Tabelas</h2>";

$tables = ['users', 'profiles', 'favorites', 'identifications'];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $table");
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $result['count'];
        
        echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "📋 <strong>$table:</strong> $count registos";
        echo "</div>";
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "❌ Erro ao contar registos em $table: " . $e->getMessage();
        echo "</div>";
    }
}

// Mostrar estrutura das tabelas
echo "<h2>🏗️ Estrutura das Tabelas</h2>";

foreach ($tables as $table) {
    echo "<h3>📋 Tabela: $table</h3>";
    try {
        $stmt = $pdo->prepare("DESCRIBE $table");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background: #e9ecef;'>";
        echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Campo</th>";
        echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Tipo</th>";
        echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Null</th>";
        echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Chave</th>";
        echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Default</th>";
        echo "</tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>" . $column['Field'] . "</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>" . $column['Type'] . "</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>" . $column['Null'] . "</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>" . $column['Key'] . "</td>";
            echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>" . $column['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "❌ Erro ao mostrar estrutura de $table: " . $e->getMessage();
        echo "</div>";
    }
}

// Mostrar alguns dados de exemplo
echo "<h2>📝 Dados de Exemplo</h2>";

foreach ($tables as $table) {
    echo "<h3>📋 Últimos registos de: $table</h3>";
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT 5");
        $stmt->execute();
        $records = $stmt->fetchAll();
        
        if (count($records) > 0) {
            echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
            
            // Cabeçalho
            echo "<tr style='background: #e9ecef;'>";
            foreach (array_keys($records[0]) as $header) {
                echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>$header</th>";
            }
            echo "</tr>";
            
            // Dados
            foreach ($records as $record) {
                echo "<tr>";
                foreach ($record as $value) {
                    $display_value = is_null($value) ? 'NULL' : (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value);
                    echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>$display_value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: #6c757d; font-style: italic;'>Nenhum registo encontrado.</p>";
        }
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "❌ Erro ao mostrar dados de $table: " . $e->getMessage();
        echo "</div>";
    }
}

echo "<h2>🔗 Como Aceder ao phpMyAdmin</h2>";
echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>📋 Passos para aceder ao phpMyAdmin:</h3>";
echo "<ol>";
echo "<li>Abra o navegador</li>";
echo "<li>Vá para: <strong>http://localhost/phpmyadmin</strong></li>";
echo "<li>Utilizador: <strong>root</strong></li>";
echo "<li>Password: <strong>(deixe em branco)</strong></li>";
echo "<li>Clique em 'Continuar'</li>";
echo "<li>No painel esquerdo, clique na base de dados <strong>chronos_db</strong></li>";
echo "<li>Agora pode ver todas as tabelas e dados!</li>";
echo "</ol>";
echo "</div>";

echo "<h2>📊 Tabelas Disponíveis no phpMyAdmin</h2>";
echo "<ul>";
echo "<li><strong>users</strong> - Utilizadores registados</li>";
echo "<li><strong>profiles</strong> - Perfis dos utilizadores</li>";
echo "<li><strong>favorites</strong> - Favoritos guardados</li>";
echo "<li><strong>identifications</strong> - Identificações realizadas</li>";
echo "</ul>";

echo "<h2>🔧 Links Úteis</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/phpmyadmin' target='_blank'>🔗 phpMyAdmin</a></li>";
echo "<li><a href='install.php'>🔧 Instalador do Sistema</a></li>";
echo "<li><a href='test_system.php'>🧪 Teste do Sistema</a></li>";
echo "<li><a href='login.php'>🔐 Login</a></li>";
echo "<li><a href='perfil.php'>👤 Perfil</a></li>";
echo "</ul>";

echo "<div style='background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>💡 Dica:</h3>";
echo "<p>Para ver os dados em tempo real, aceda ao phpMyAdmin e navegue para a base de dados <strong>chronos_db</strong>. Todas as ações do sistema (registo, login, favoritos, etc.) serão guardadas nas tabelas correspondentes.</p>";
echo "</div>";

echo "<p><em>🎓 Sistema desenvolvido para o projeto final de programação web</em></p>";
?> 