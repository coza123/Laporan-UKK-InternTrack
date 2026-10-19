<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$msg = ''; $msgType = 'success';
$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $siswa_id = (int)$_POST['siswa_id'];
        $tanggal = $_POST['tanggal'];
        $catatan = trim($_POST['catatan'] ?? '');
        $nilai = floatval($_POST['nilai'] ?? 0);
        $rekomendasi = trim($_POST['rekomendasi'] ?? '');
        $pembimbing_id = $user['role'] === 'pembimbing' ? (int)$user['related_id'] : (int)$_POST['pembimbing_id'];
        
        $stmt = $conn->prepare("INSERT INTO monitoring (siswa_id, pembimbing_id, tanggal, catatan, nilai, rekomendasi) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("iisdss", $siswa_id, $pembimbing_id, $tanggal, $catatan, $nilai, $rekomendasi);
        $stmt->execute();
        $msg = 'Monitoring berhasil disimpan.';
    }
}

$list = $conn->query("SELECT m.*, s.nama as siswa, p.nama as pembimbing FROM monitoring m 
    JOIN siswa s ON m.siswa_id = s.id 
    JOIN pembimbing p ON m.pembimbing_id = p.id 
    ORDER BY m.tanggal DESC");

$siswa_opts = $conn->query("SELECT id, nama FROM siswa ORDER BY nama");
$pembimbing_opts = $conn->query("SELECT id, nama FROM pembimbing ORDER BY nama");

$pageTitle = 'Monitoring';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-clipboard-check"></i> Monitoring PKL</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg"></i> Input Monitoring</button>
    </div>
    <?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Pembimbing</th><th>Nilai</th><th>Catatan</th><th>Rekomendasi</th></tr></thead>
            <tbody>
            <?php while ($m = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($m['siswa']) ?></td>
                    <td><?= htmlspecialchars($m['pembimbing']) ?></td>
                    <td><strong><?= number_format($m['nilai'], 1) ?></strong></td>
                    <td><?= htmlspecialchars(mb_strimwidth($m['catatan'] ?? '-', 0, 50, '...')) ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($m['rekomendasi'] ?? '-', 0, 40, '...')) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="tambah">
    <div class="modal-header"><h5 class="modal-title">Input Monitoring</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-2"><label>Siswa *</label><select name="siswa_id" class="form-select" required>
            <option value="">Pilih Siswa</option>
            <?php while ($s = $siswa_opts->fetch_assoc()): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option><?php endwhile; ?>
        </select></div>
        <?php if ($user['role'] === 'admin'): ?>
        <div class="mb-2"><label>Pembimbing *</label><select name="pembimbing_id" class="form-select" required>
            <option value="">Pilih</option>
            <?php while ($b = $pembimbing_opts->fetch_assoc()): ?><option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nama']) ?></option><?php endwhile; ?>
        </select></div>
        <?php endif; ?>
        <div class="mb-2"><label>Tanggal *</label><input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
        <div class="mb-2"><label>Catatan</label><textarea name="catatan" class="form-control" rows="3"></textarea></div>
        <div class="mb-2"><label>Nilai</label><input type="number" name="nilai" class="form-control" min="0" max="100" step="0.01" value="80"></div>
        <div class="mb-2"><label>Rekomendasi</label><textarea name="rekomendasi" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
