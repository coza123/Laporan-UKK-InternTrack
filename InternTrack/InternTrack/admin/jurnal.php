<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$search = trim($_GET['q'] ?? '');
$where = $search ? "AND (s.nama LIKE '%".$conn->real_escape_string($search)."%' OR j.kegiatan LIKE '%".$conn->real_escape_string($search)."%')" : "";
$list = $conn->query("SELECT j.*, s.nama as siswa FROM jurnal j JOIN siswa s ON j.siswa_id = s.id WHERE 1=1 $where ORDER BY j.tanggal DESC LIMIT 100");

$pageTitle = 'Jurnal Kegiatan';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-journal-text"></i> Jurnal Kegiatan Siswa</h4>
    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6"><input type="text" name="q" class="form-control" placeholder="Cari siswa / kegiatan..." value="<?= htmlspecialchars($search) ?>"></div>
            <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Cari</button></div>
        </form>
    </div></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kegiatan</th><th>Hasil</th><th>Kendala</th></tr></thead>
            <tbody>
            <?php while ($j = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($j['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($j['siswa']) ?></td>
                    <td><?= htmlspecialchars($j['kegiatan']) ?></td>
                    <td><?= htmlspecialchars($j['hasil'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($j['kendala'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
