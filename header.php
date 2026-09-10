<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Reading Room — Library System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Source+Sans+3:wght@400;500;600&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4.5C4 3.7 4.7 3 5.5 3H11V20H5.5C4.7 20 4 19.3 4 18.5V4.5Z" stroke="#C79A4B" stroke-width="1.4" stroke-linejoin="round"/>
                    <path d="M20 4.5C20 3.7 19.3 3 18.5 3H13V20H18.5C19.3 20 20 19.3 20 18.5V4.5Z" stroke="#C79A4B" stroke-width="1.4" stroke-linejoin="round"/>
                </svg>
                The Reading Room
            </a>
            <div class="nav-links">
                <a href="index.php">Catalog</a>
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="admin.php" class="btn-admin-link">Dashboard</a>
                    <a href="borrow-history.php">Borrow history</a>
                    <a href="logout.php" class="btn-logout">Log out (<?= htmlspecialchars($_SESSION['admin_name']) ?>)</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Admin log in</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container main-content">
