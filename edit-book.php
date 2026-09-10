<?php
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: admin.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $status = $_POST['status'] ?? 'available';

    if (strlen($title) < 2)  $errors[] = 'Title must be at least 2 characters long.';
    if (strlen($author) < 2) $errors[] = 'Author must be at least 2 characters long.';
    if (strlen(str_replace(['-', ' '], '', $isbn)) < 10) $errors[] = 'ISBN looks too short.';
    if (!in_array($status, ['available', 'borrowed'], true)) $status = 'available';

    if (empty($errors)) {
        $update = $pdo->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, status = ? WHERE id = ?");
        $update->execute([$title, $author, $isbn, $status, $id]);
        header("Location: admin.php");
        exit();
    } else {
        // Keep the submitted values on screen if validation fails
        $book = array_merge($book, compact('title', 'author', 'isbn', 'status'));
    }
}

include 'header.php';
?>

<div class="page-hero">
    <h1>Edit book</h1>
    <p>Update the details for this title.</p>
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
                   value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author"
                   value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn"
                   value="<?= htmlspecialchars($book['isbn']) ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="borrowed" <?= $book['status'] === 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Save changes</button>
    </form>
</div>

<?php include 'footer.php'; ?>
