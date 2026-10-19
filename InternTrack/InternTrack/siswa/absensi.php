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
    $status = $_POST['status'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');
    if (empty($tanggal) || empty($status)) {
        $msg = 'Tanggal dan Status wajib diisi.'; $msgType = 'danger';
    } else {
        $check = $conn->prepare("SELECT id FROM absensi WHERE siswa_id = ? AND tanggal = ?");
        $check->bind_param("is", $siswa_id, $tanggal);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $msg = 'Absensi untuk tanggal tersebut sudah ada.'; $msgType = 'danger';
        } else {
            $penempatan = $conn->query("SELECT id FROM penempatan WHERE siswa_id = $siswa_id AND status = 'Aktif' LIMIT 1")->fetch_assoc();
            $pen_id = $penempatan['id'] ?? null;
            $stmt = $conn->prepare("INSERT INTO absensi (siswa_id, penempatan_id, tanggal, status, keterangan) VALUES (?,?,?,?,?)");
            $stmt->bind_param("iisss", $siswa_id, $pen_id, $tanggal, $status, $keterangan);
            $stmt->execute();
            $msg = 'Absensi berhasil disimpan.';
        }
    }
}

$list = $conn->query("SELECT * FROM absensi WHERE siswa_id = $siswa_id ORDER BY tanggal DESC");
$pct = hitungPersentaseKehadiran($siswa_id);
$status_kh = getStatusKehadiran($pct);

$pageTitle = 'Absensi';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-calendar-check"></i> Absensi Saya</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg"></i> Isi Absensi</button>
    </div>
    <?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="alert alert-info">
        Persentase Kehadiran: <strong><?= $pct ?>%</strong> — Status: <span class="badge bg-<?= $status_kh['class'] ?>"><?= $status_kh['label'] ?></span>
    </div>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
            <?php while ($a = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                    <td><span class="badge bg-<?= $a['status']==='Hadir'?'success':($a['status']==='Alpa'?'danger':'warning') ?>"><?= $a['status'] ?></span></td>
                    <td><?= htmlspecialchars($a['keterangan'] ?? '-') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form method="POST">
    <div class="modal-header"><h5 class="modal-title">Isi Absensi</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-2"><label>Tanggal *</label><input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
        <div class="mb-2"><label>Status *</label><select name="status" class="form-select" required>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alpa">Alpa</option>
        </select></div>
        <div class="mb-2"><label>Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
