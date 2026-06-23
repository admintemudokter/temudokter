<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=8889', 'root', 'root');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS temudokter');
    
    // Check if tables exist in old database
    $stmt = $pdo->query("SHOW TABLES IN konsulku");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS temudokter.$table LIKE konsulku.$table");
            $pdo->exec("INSERT IGNORE INTO temudokter.$table SELECT * FROM konsulku.$table");
        }
        echo "Database 'temudokter' created and all data copied successfully!\n";
    } else {
        echo "Database 'temudokter' created, but 'konsulku' was empty.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
