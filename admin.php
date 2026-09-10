<?php
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Toggle a book's status directly from the table (available <-> borrowed)
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("SELECT status FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetchColumn();
    if ($current) {
        $new = $current === 'available' ? 'borrowed' : 'available';
        $pdo->prepare("UPDATE books SET status = ? WHERE id = ?")->execute([$new, $id]);
    }
    header("Location: admin.php");
    exit();
}

// Delete a book
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM books WHERE id = ?")->execute([$id]);
    header("Location: admin.php");
    exit();
}

$total = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$available = (int)$pdo->query("SELECT COUNT(*) FROM books WHERE status = 'available'")->fetchColumn();
$borrowed = (int)$pdo->query("SELECT COUNT(*) FROM books WHERE status = 'borrowed'")->fetchColumn();

$books = $pdo->query("SELECT * FROM books ORDER BY created_at DESC")->fetchAll();

include 'header.php';
?>

<div class="dashboard-header">
    <div>
        <h1>Dashboard</h1>
        <p style="color: var(--text-muted); margin: 0;">Manage the book catalog.</p>
    </div>
    <a href="add-book.php" class="btn-nav">Add a book</a>
</div>

<div class="ledger">
    <div class="ledger-item">
        <div class="label">Total books</div>
        <div class="value"><?= $total ?></div>
    </div>
    <div class="ledger-item accent-available">
        <div class="label">Available</div>
        <div class="value"><?= $available ?></div>
    </div>
    <div class="ledger-item accent-borrowed">
        <div class="label">Borrowed</div>
        <div class="value"><?= $borrowed ?></div>
    </div>
</div>

<div class="card-table">
    <?php if (empty($books)): ?>
        <p>No books yet — add the first one.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                    <?php $isAvailable = $b['status'] === 'available'; ?>
                    <tr>
                        <td data-label="Title"><?= htmlspecialchars($b['title']) ?></td>
                        <td data-label="Author"><?= htmlspecialchars($b['author']) ?></td>
                        <td data-label="ISBN"><code><?= htmlspecialchars($b['isbn']) ?></code></td>
                        <td data-label="Status">
                            <span class="badge <?= $isAvailable ? 'badge-available' : 'badge-borrowed' ?>">
                                <?= $isAvailable ? 'Available' : 'Borrowed' ?>
                            </span>
                        </td>
                        <td data-label="Actions">
                            <a href="edit-book.php?id=<?= (int)$b['id'] ?>" class="btn-sm btn-edit">Edit</a>
                            <a href="admin.php?toggle=<?= (int)$b['id'] ?>" class="btn-sm btn-borrow">
                                Mark as <?= $isAvailable ? 'borrowed' : 'available' ?>
                            </a>
                            <a href="admin.php?delete=<?= (int)$b['id'] ?>" class="btn-sm btn-delete"
                               data-title="<?= htmlspecialchars($b['title']) ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
