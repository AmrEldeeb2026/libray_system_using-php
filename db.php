<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'library_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Seed a default admin account on first run, hashed with this PHP
// installation's own password_hash() so it always verifies correctly.
try {
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $hashed = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")
            ->execute(['Library Admin', 'admin@library.com', $hashed]);
    }
} catch (PDOException $e) {
    // If the `users` table doesn't exist yet, database.sql hasn't been
    // imported — the pages below will simply show no admin available.
}
?>
