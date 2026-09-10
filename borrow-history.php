<?php
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Mark a borrow record as returned, and free up the book again
if (isset($_GET['return'])) {
    $recordId = (int)$_GET['return'];

    $stmt = $pdo->prepare("SELECT book_id FROM borrow_records WHERE id = ?");
    $stmt->execute([$recordId]);
    $bookId = $stmt->fetchColumn();

    if ($bookId) {
        $pdo->beginTransaction();
        $pdo->prepare("UPDATE borrow_records SET status = 'returned', return_date = CURDATE() WHERE id = ?")
            ->execute([$recordId]);
        $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?")
            ->execute([$bookId]);
        $pdo->commit();
    }
    header("Location: borrow-history.php");
    exit();
}

$records = $pdo->query(
    "SELECT br.*, b.title, b.author
     FROM borrow_records br
     JOIN books b ON b.id = br.book_id
     ORDER BY br.created_at DESC"
)->fetchAll();

include 'header.php';
?>

<div class="page-hero">
    <h1>Borrow history</h1>
    <p>Every borrow request recorded in the system.</p>
</div>

<div class="card-table">
    <?php if (empty($records)): ?>
        <p>No borrow requests yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Borrower</th>
                    <th>Phone</th>
                    <th>Borrowed on</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                    <?php $isBorrowed = $r['status'] === 'borrowed'; ?>
                    <tr>
                        <td data-label="Book"><?= htmlspecialchars($r['title']) ?></td>
                        <td data-label="Borrower"><?= htmlspecialchars($r['borrower_name']) ?></td>
                        <td data-label="Phone"><?= htmlspecialchars($r['borrower_phone']) ?></td>
                        <td data-label="Borrowed on"><?= htmlspecialchars($r['borrow_date']) ?></td>
                        <td data-label="Status">
                            <span class="badge <?= $isBorrowed ? 'badge-borrowed' : 'badge-available' ?>">
                                <?= $isBorrowed ? 'Borrowed' : 'Returned' ?>
                            </span>
                        </td>
                        <td data-label="Actions">
                            <?php if ($isBorrowed): ?>
                                <a href="borrow-history.php?return=<?= (int)$r['id'] ?>" class="btn-sm btn-borrow">Mark returned</a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.85rem;">
                                    Returned on <?= htmlspecialchars($r['return_date']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
