<?php
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');

    if (strlen($title) < 2)  $errors[] = 'Title must be at least 2 characters long.';
    if (strlen($author) < 2) $errors[] = 'Author must be at least 2 characters long.';
    if (strlen(str_replace(['-', ' '], '', $isbn)) < 10) $errors[] = 'ISBN looks too short.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, status) VALUES (?, ?, ?, 'available')");
        $stmt->execute([$title, $author, $isbn]);
        header("Location: admin.php");
        exit();
    }
}

include 'header.php';
?>

<div class="page-hero">
    <h1>Add a book</h1>
    <p>Add a new title to the catalog.</p>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert-error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" id="bookForm">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author"
                   value="<?= htmlspecialchars($_POST['author'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn"
                   value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn-submit">Add book</button>
    </form>
</div>

<?php include 'footer.php'; ?>
