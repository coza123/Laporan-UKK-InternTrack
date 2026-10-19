<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole('siswa');

$conn = getConnection();
$user = getCurrentUser();
$siswa_id = (int)$user['related_id'];

$siswa = $conn->query("SELECT * FROM siswa WHERE id = $siswa_id")->fetch_assoc();
$penempatan = $conn->query("SELECT pn.*, pr.nama as perusahaan, pb.nama as pembimbing 
    FROM penempatan pn 
    JOIN perusahaan pr ON pn.perusahaan_id = pr.id 
    JOIN pembimbing pb ON pn.pembimbing_id = pb.id 
    WHERE pn.siswa_id = $siswa_id ORDER BY pn.id DESC LIMIT 1")->fetch_assoc();

$pct = hitungPersentaseKehadiran($siswa_id);
$status_kh = getStatusKehadiran($pct);

$jurnal_count = $conn->query("SELECT COUNT(*) as c FROM jurnal WHERE siswa_id = $siswa_id")->fetch_assoc()['c'];
$absensi_count = $conn->query("SELECT COUNT(*) as c FROM absensi WHERE siswa_id = $siswa_id")->fetch_assoc()['c'];

$pageTitle = 'Dashboard Siswa';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4">Halo, <?= htmlspecialchars($siswa['nama']) ?>!</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Tempat PKL</h6>
                    <h5><?= $penempatan ? htmlspecialchars($penempatan['perusahaan']) : 'Belum ditempatkan' ?></h5>
                    <?php if ($penempatan): ?>
                        <span class="badge bg-<?= $penempatan['status']==='Aktif'?'success':($penempatan['status']==='Selesai'?'primary':'secondary') ?>"><?= $penempatan['status'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Pembimbing</h6>
                    <h5><?= $penempatan ? htmlspecialchars($penempatan['pembimbing']) : '-' ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Status Kehadiran</h6>
                    <h5><?= $pct ?>% <span class="badge bg-<?= $status_kh['class'] ?>"><?= $status_kh['label'] ?></span></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?= $jurnal_count ?></h3>
                    <p class="mb-0">Jurnal Kegiatan</p>
                    <a href="<?= htmlspecialchars(appUrl('siswa/jurnal.php')) ?>" class="btn btn-sm btn-outline-primary mt-2">Kelola Jurnal</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?= $absensi_count ?></h3>
                    <p class="mb-0">Data Absensi</p>
                    <a href="<?= htmlspecialchars(appUrl('siswa/absensi.php')) ?>" class="btn btn-sm btn-outline-primary mt-2">Isi Absensi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
