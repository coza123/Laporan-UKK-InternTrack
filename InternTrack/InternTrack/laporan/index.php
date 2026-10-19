<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$jenis = $_GET['jenis'] ?? '';

$pageTitle = 'Laporan';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="?jenis=siswa" class="card text-decoration-none text-dark h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-primary"></i>
                    <h5 class="mt-2">Laporan Data Siswa PKL</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="?jenis=kehadiran" class="card text-decoration-none text-dark h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check display-4 text-success"></i>
                    <h5 class="mt-2">Laporan Kehadiran</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="?jenis=monitoring" class="card text-decoration-none text-dark h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check display-4 text-info"></i>
                    <h5 class="mt-2">Laporan Monitoring</h5>
                </div>
            </a>
        </div>
    </div>

    <?php if ($jenis === 'siswa'): 
        $data = $conn->query("SELECT s.*, pn.status as status_pkl, pr.nama as perusahaan 
            FROM siswa s 
            LEFT JOIN penempatan pn ON s.id = pn.siswa_id 
            LEFT JOIN perusahaan pr ON pn.perusahaan_id = pr.id 
            ORDER BY s.nama");
    ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Laporan Data Siswa PKL</span>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            </div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Perusahaan</th><th>Status PKL</th></tr></thead>
                    <tbody>
                    <?php $no=1; while ($r = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= htmlspecialchars($r['kelas']) ?></td>
                            <td><?= htmlspecialchars($r['perusahaan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($r['status_pkl'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div></div>
        </div>
    <?php elseif ($jenis === 'kehadiran'): 
        $data = $conn->query("SELECT s.nis, s.nama, s.kelas,
            COUNT(a.id) as total,
            SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as alpa
            FROM siswa s LEFT JOIN absensi a ON s.id = a.siswa_id
            GROUP BY s.id ORDER BY s.nama");
    ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Laporan Kehadiran</span>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            </div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Total</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpa</th><th>%</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php $no=1; while ($r = $data->fetch_assoc()): 
                        $pct = $r['total'] > 0 ? round(($r['hadir']/$r['total'])*100,1) : 0;
                        $st = getStatusKehadiran($pct);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><?= $r['total'] ?></td>
                            <td><?= $r['hadir'] ?></td>
                            <td><?= $r['izin'] ?></td>
                            <td><?= $r['sakit'] ?></td>
                            <td><?= $r['alpa'] ?></td>
                            <td><?= $pct ?>%</td>
                            <td><?= $st['label'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div></div>
        </div>
    <?php elseif ($jenis === 'monitoring'): 
        $data = $conn->query("SELECT m.*, s.nama as siswa, s.nis, p.nama as pembimbing 
            FROM monitoring m 
            JOIN siswa s ON m.siswa_id = s.id 
            JOIN pembimbing p ON m.pembimbing_id = p.id 
            ORDER BY m.tanggal DESC");
    ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Laporan Monitoring</span>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            </div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead><tr><th>No</th><th>Tanggal</th><th>NIS</th><th>Siswa</th><th>Pembimbing</th><th>Nilai</th><th>Catatan</th></tr></thead>
                    <tbody>
                    <?php $no=1; while ($r = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($r['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($r['nis']) ?></td>
                            <td><?= htmlspecialchars($r['siswa']) ?></td>
                            <td><?= htmlspecialchars($r['pembimbing']) ?></td>
                            <td><?= number_format($r['nilai'],1) ?></td>
                            <td><?= htmlspecialchars(mb_strimwidth($r['catatan']??'-',0,40,'...')) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div></div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
