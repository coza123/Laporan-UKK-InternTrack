<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$list = $conn->query("SELECT p.*, u.username FROM pembimbing p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.nama");

$pageTitle = 'Data Pembimbing';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-person-badge"></i> Data Guru Pembimbing</h4>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>NIP</th><th>Nama</th><th>No. HP</th><th>Email</th><th>Username</th></tr></thead>
            <tbody>
            <?php while ($p = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nip'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['nama']) ?></td>
                    <td><?= htmlspecialchars($p['no_hp'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['username'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
