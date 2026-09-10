<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['borrower_name'] ?? '');
    $phone = trim($_POST['borrower_phone'] ?? '');
    $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');

    if ($name === '') {
        $errors[] = 'Borrower name is required.';
    }
    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    }
    if ($book['status'] !== 'available') {
        $errors[] = 'Sorry, this book is no longer available.';
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            $insert = $pdo->prepare(
                "INSERT INTO borrow_records (book_id, borrower_name, borrower_phone, borrow_date, status)
                 VALUES (?, ?, ?, ?, 'borrowed')"
            );
            $insert->execute([$id, $name, $phone, $borrow_date]);

            $update = $pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?");
            $update->execute([$id]);

            $pdo->commit();
            header("Location: index.php?msg=borrowed");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Something went wrong while saving your request. Please try again.';
        }
    }
}

include 'header.php';
?>

<div class="page-hero">
    <h1>Request to borrow</h1>
    <p><?= htmlspecialchars($book['title']) ?> — by <?= htmlspecialchars($book['author']) ?></p>
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
    <form method="POST" id="borrowForm">
        <div class="form-group">
            <label for="borrower_name">Your name</label>
            <input type="text" id="borrower_name" name="borrower_name"
                   value="<?= htmlspecialchars($_POST['borrower_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="borrower_phone">Phone number</label>
            <input type="text" id="borrower_phone" name="borrower_phone"
                   value="<?= htmlspecialchars($_POST['borrower_phone'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="borrow_date">Borrow date</label>
            <input type="date" id="borrow_date" name="borrow_date"
                   value="<?= htmlspecialchars($_POST['borrow_date'] ?? date('Y-m-d')) ?>" required>
        </div>

        <button type="submit" class="btn-submit">Confirm request</button>
    </form>
</div>

<?php include 'footer.php'; ?>
