<?php
require_once 'db.php';

$msg = '';
$msg_type = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'borrowed') {
    $msg = 'Your borrow request has been recorded. Please collect the book from the front desk.';
    $msg_type = 'success';
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
$books = $stmt->fetchAll();

include 'header.php';
?>

<div class="page-hero">
    <h1>Browse the catalog</h1>
    <p>Find a title below and request to borrow it — no account needed.</p>
</div>

<?php if ($msg): ?>
    <div class="alert-<?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="search-box">
    <input type="text" id="bookSearch" placeholder="Search by title or author...">
</div>

<?php if (empty($books)): ?>
    <p>No books in the catalog yet.</p>
<?php else: ?>
    <div class="book-grid">
        <?php foreach ($books as $b): ?>
            <?php
                $isAvailable = $b['status'] === 'available';
                $searchKey = strtolower($b['title'] . ' ' . $b['author']);
            ?>
            <div class="book-card <?= $isAvailable ? 'is-available' : 'is-borrowed' ?>"
                 data-search="<?= htmlspecialchars($searchKey) ?>">
                <div class="book-title"><?= htmlspecialchars($b['title']) ?></div>
                <div class="book-author">by <?= htmlspecialchars($b['author']) ?></div>
                <div class="book-isbn">ISBN <code><?= htmlspecialchars($b['isbn']) ?></code></div>
                <div class="book-status status-<?= $isAvailable ? 'available' : 'borrowed' ?>">
                    <span class="dot"></span>
                    <?= $isAvailable ? 'Available' : 'Currently borrowed' ?>
                </div>
                <div class="book-action">
                    <?php if ($isAvailable): ?>
                        <a href="borrow.php?id=<?= (int)$b['id'] ?>" class="link-action">Request to borrow</a>
                    <?php else: ?>
                        <span class="link-action is-disabled">Not available</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
