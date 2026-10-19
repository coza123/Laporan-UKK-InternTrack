<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$list = $conn->query("SELECT a.*, s.nama as siswa, s.nis FROM absensi a JOIN siswa s ON a.siswa_id = s.id ORDER BY a.tanggal DESC LIMIT 100");

// Rekap per siswa
$rekap = $conn->query("SELECT s.id, s.nama, s.nis,
    COUNT(a.id) as total,
    SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as izin,
    SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as sakit,
    SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as alpa
    FROM siswa s LEFT JOIN absensi a ON s.id = a.siswa_id
    GROUP BY s.id HAVING total > 0 ORDER BY s.nama");

$pageTitle = 'Absensi';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-calendar-check"></i> Data Absensi & Rekap Kehadiran</h4>

    <div class="card mb-4">
        <div class="card-header">Rekap Kehadiran Siswa</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>NIS</th><th>Nama</th><th>Total</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpa</th><th>Persentase</th><th>Status</th></tr></thead>
                <tbody>
                <?php while ($r = $rekap->fetch_assoc()): 
                    $pct = $r['total'] > 0 ? round(($r['hadir'] / $r['total']) * 100, 1) : 0;
                    $st = getStatusKehadiran($pct);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nis']) ?></td>
                        <td><?= htmlspecialchars($r['nama']) ?></td>
                        <td><?= $r['total'] ?></td>
                        <td><?= $r['hadir'] ?></td>
                        <td><?= $r['izin'] ?></td>
                        <td><?= $r['sakit'] ?></td>
                        <td><?= $r['alpa'] ?></td>
                        <td><strong><?= $pct ?>%</strong></td>
                        <td><span class="badge bg-<?= $st['class'] ?>"><?= $st['label'] ?></span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div></div>
    </div>

    <div class="card">
        <div class="card-header">Riwayat Absensi Terbaru</div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Tanggal</th><th>Siswa</th><th>Status</th><th>Keterangan</th></tr></thead>
                <tbody>
                <?php while ($a = $list->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($a['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($a['siswa']) ?></td>
                        <td><span class="badge bg-<?= $a['status']==='Hadir'?'success':($a['status']==='Alpa'?'danger':'warning') ?>"><?= $a['status'] ?></span></td>
                        <td><?= htmlspecialchars($a['keterangan'] ?? '-') ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div></div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
