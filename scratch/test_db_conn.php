<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=londonstreet', 'root', '');
    echo "CONNECTED_SUCCESS\n";
    $stmt = $pdo->query("SELECT count(*) FROM users");
    echo "User count: " . $stmt->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
