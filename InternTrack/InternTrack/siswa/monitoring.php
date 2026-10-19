<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole('siswa');

$conn = getConnection();
$user = getCurrentUser();
$siswa_id = (int)$user['related_id'];

$list = $conn->query("SELECT m.*, p.nama as pembimbing FROM monitoring m 
    JOIN pembimbing p ON m.pembimbing_id = p.id 
    WHERE m.siswa_id = $siswa_id ORDER BY m.tanggal DESC");

$pageTitle = 'Hasil Monitoring';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-clipboard-check"></i> Hasil Monitoring</h4>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Pembimbing</th><th>Nilai</th><th>Catatan</th><th>Rekomendasi</th></tr></thead>
            <tbody>
            <?php if ($list->num_rows == 0): ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada data monitoring.</td></tr>
            <?php endif; ?>
            <?php while ($m = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($m['pembimbing']) ?></td>
                    <td><strong><?= number_format($m['nilai'], 1) ?></strong></td>
                    <td><?= htmlspecialchars($m['catatan'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['rekomendasi'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
