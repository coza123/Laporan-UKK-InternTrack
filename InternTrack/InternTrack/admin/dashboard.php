<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();

// Stats from DB
$total_siswa = $conn->query("SELECT COUNT(*) as c FROM siswa")->fetch_assoc()['c'];
$total_perusahaan = $conn->query("SELECT COUNT(*) as c FROM perusahaan")->fetch_assoc()['c'];
$total_pembimbing = $conn->query("SELECT COUNT(*) as c FROM pembimbing")->fetch_assoc()['c'];
$pkl_aktif = $conn->query("SELECT COUNT(*) as c FROM penempatan WHERE status = 'Aktif'")->fetch_assoc()['c'];
$pkl_selesai = $conn->query("SELECT COUNT(*) as c FROM penempatan WHERE status = 'Selesai'")->fetch_assoc()['c'];
$jurnal_hari_ini = $conn->query("SELECT COUNT(*) as c FROM jurnal WHERE tanggal = CURDATE()")->fetch_assoc()['c'];

// Siswa perlu perhatian (persentase < 75)
$siswa_perlu = 0;
$res = $conn->query("SELECT siswa_id FROM absensi GROUP BY siswa_id");
while ($row = $res->fetch_assoc()) {
    $pct = hitungPersentaseKehadiran($row['siswa_id']);
    if ($pct < 75 && $pct > 0) $siswa_perlu++;
}

$pageTitle = 'Dashboard Admin';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard InternTrack</h4>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Total Siswa PKL</h6>
                        <h2 class="mb-0"><?= $total_siswa ?></h2>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Perusahaan</h6>
                        <h2 class="mb-0"><?= $total_perusahaan ?></h2>
                    </div>
                    <i class="bi bi-building stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">Pembimbing</h6>
                        <h2 class="mb-0"><?= $total_pembimbing ?></h2>
                    </div>
                    <i class="bi bi-person-badge stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 opacity-75">PKL Aktif</h6>
                        <h2 class="mb-0"><?= $pkl_aktif ?></h2>
                    </div>
                    <i class="bi bi-activity stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">PKL Selesai</h6>
                    <h3 class="text-primary"><?= $pkl_selesai ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Jurnal Hari Ini</h6>
                    <h3 class="text-success"><?= $jurnal_hari_ini ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Siswa Perlu Perhatian</h6>
                    <h3 class="text-danger"><?= $siswa_perlu ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Aktivitas Terbaru (Jurnal)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kegiatan</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $jurnals = $conn->query("SELECT j.*, s.nama FROM jurnal j JOIN siswa s ON j.siswa_id = s.id ORDER BY j.tanggal DESC, j.id DESC LIMIT 10");
                        while ($j = $jurnals->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($j['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($j['nama']) ?></td>
                            <td><?= htmlspecialchars(mb_strimwidth($j['kegiatan'], 0, 50, '...')) ?></td>
                            <td><?= htmlspecialchars(mb_strimwidth($j['hasil'] ?? '-', 0, 40, '...')) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
