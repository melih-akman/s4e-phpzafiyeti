<?php
$db_host = 'localhost';
$db_name = 'security_demo';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $db_file = __DIR__ . '/security_demo.db';
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

function initDatabase($pdo) {
    try {
        $result = $pdo->query("SELECT 1 FROM users LIMIT 1");
    } catch (PDOException $e) {
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            password TEXT NOT NULL,
            email TEXT NOT NULL,
            full_name TEXT,
            is_admin INTEGER DEFAULT 0,
            credit_card TEXT,
            balance REAL DEFAULT 0
        )");
        
        $pdo->exec("INSERT INTO users (username, password, email, full_name, is_admin, credit_card, balance) 
                   VALUES 
                   ('admin', 'admin123', 'admin@example.com', 'Admin User', 1, '1234-5678-9012-3456', 9500.75),
                   ('john', 'password123', 'john@example.com', 'John Doe', 0, '9876-5432-1098-7654', 1200.50),
                   ('jane', 'jane123', 'jane@example.com', 'Jane Smith', 0, '5555-4444-3333-2222', 750.25)");
    }
    
    try {
        $result = $pdo->query("SELECT 1 FROM comments LIMIT 1");
    } catch (PDOException $e) {
        $pdo->exec("CREATE TABLE comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            comment TEXT NOT NULL,
            date DATETIME DEFAULT CURRENT_TIMESTAMP,
            user_id INTEGER
        )");
        
        $pdo->exec("INSERT INTO comments (name, comment, date) 
                   VALUES 
                   ('Admin', 'Merhaba! Bu bir demo yorumdur.', datetime('now', '-1 hour')),
                   ('John Doe', 'Bu site harika görünüyor!', datetime('now', '-30 minutes'))");
    }
}

initDatabase($pdo);
?>
