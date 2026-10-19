<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole('siswa');

$conn = getConnection();
$user = getCurrentUser();
$siswa_id = (int)$user['related_id'];
$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $kegiatan = trim($_POST['kegiatan'] ?? '');
    $hasil = trim($_POST['hasil'] ?? '');
    $kendala = trim($_POST['kendala'] ?? '');
    if (empty($tanggal) || empty($kegiatan)) {
        $msg = 'Tanggal dan Kegiatan wajib diisi.'; $msgType = 'danger';
    } else {
        $penempatan = $conn->query("SELECT id FROM penempatan WHERE siswa_id = $siswa_id AND status = 'Aktif' LIMIT 1")->fetch_assoc();
        $pen_id = $penempatan['id'] ?? null;
        $stmt = $conn->prepare("INSERT INTO jurnal (siswa_id, penempatan_id, tanggal, kegiatan, hasil, kendala) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("iissss", $siswa_id, $pen_id, $tanggal, $kegiatan, $hasil, $kendala);
        $stmt->execute();
        $msg = 'Jurnal berhasil disimpan.';
    }
}

$list = $conn->query("SELECT * FROM jurnal WHERE siswa_id = $siswa_id ORDER BY tanggal DESC");

$pageTitle = 'Jurnal Kegiatan';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-journal-text"></i> Jurnal Kegiatan Saya</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg"></i> Isi Jurnal</button>
    </div>
    <?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Hasil</th><th>Kendala</th></tr></thead>
            <tbody>
            <?php while ($j = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($j['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($j['kegiatan']) ?></td>
                    <td><?= htmlspecialchars($j['hasil'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($j['kendala'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form method="POST">
    <div class="modal-header"><h5 class="modal-title">Isi Jurnal Kegiatan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-2"><label>Tanggal *</label><input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
        <div class="mb-2"><label>Kegiatan *</label><textarea name="kegiatan" class="form-control" rows="2" required></textarea></div>
        <div class="mb-2"><label>Hasil</label><textarea name="hasil" class="form-control" rows="2"></textarea></div>
        <div class="mb-2"><label>Kendala</label><textarea name="kendala" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
