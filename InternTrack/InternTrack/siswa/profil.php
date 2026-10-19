<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole('siswa');

$conn = getConnection();
$user = getCurrentUser();
$siswa_id = (int)$user['related_id'];
$siswa = $conn->query("SELECT * FROM siswa WHERE id = $siswa_id")->fetch_assoc();
$penempatan = $conn->query("SELECT pn.*, pr.nama as perusahaan, pr.alamat as alamat_perusahaan, pb.nama as pembimbing 
    FROM penempatan pn 
    JOIN perusahaan pr ON pn.perusahaan_id = pr.id 
    JOIN pembimbing pb ON pn.pembimbing_id = pb.id 
    WHERE pn.siswa_id = $siswa_id ORDER BY pn.id DESC LIMIT 1")->fetch_assoc();

$pageTitle = 'Profil';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-person"></i> Profil Saya</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Data Pribadi</div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th width="120">NIS</th><td><?= htmlspecialchars($siswa['nis']) ?></td></tr>
                        <tr><th>Nama</th><td><?= htmlspecialchars($siswa['nama']) ?></td></tr>
                        <tr><th>Kelas</th><td><?= htmlspecialchars($siswa['kelas']) ?></td></tr>
                        <tr><th>Jurusan</th><td><?= htmlspecialchars($siswa['jurusan']) ?></td></tr>
                        <tr><th>No. HP</th><td><?= htmlspecialchars($siswa['no_hp'] ?? '-') ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($siswa['alamat'] ?? '-') ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Tempat PKL</div>
                <div class="card-body">
                    <?php if ($penempatan): ?>
                    <table class="table table-borderless">
                        <tr><th width="140">Perusahaan</th><td><?= htmlspecialchars($penempatan['perusahaan']) ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($penempatan['alamat_perusahaan'] ?? '-') ?></td></tr>
                        <tr><th>Pembimbing</th><td><?= htmlspecialchars($penempatan['pembimbing']) ?></td></tr>
                        <tr><th>Tgl Mulai</th><td><?= date('d/m/Y', strtotime($penempatan['tgl_mulai'])) ?></td></tr>
                        <tr><th>Tgl Selesai</th><td><?= $penempatan['tgl_selesai'] ? date('d/m/Y', strtotime($penempatan['tgl_selesai'])) : '-' ?></td></tr>
                        <tr><th>Status</th><td><span class="badge bg-<?= $penempatan['status']==='Aktif'?'success':'secondary' ?>"><?= $penempatan['status'] ?></span></td></tr>
                    </table>
                    <?php else: ?>
                        <p class="text-muted">Belum ada penempatan PKL.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
