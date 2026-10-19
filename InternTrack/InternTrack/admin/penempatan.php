<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $siswa_id = (int)$_POST['siswa_id'];
        $perusahaan_id = (int)$_POST['perusahaan_id'];
        $pembimbing_id = (int)$_POST['pembimbing_id'];
        $tgl_mulai = $_POST['tgl_mulai'];
        $tgl_selesai = $_POST['tgl_selesai'] ?: null;
        $status = $_POST['status'] ?? 'Belum Mulai';
        $stmt = $conn->prepare("INSERT INTO penempatan (siswa_id, perusahaan_id, pembimbing_id, tgl_mulai, tgl_selesai, status) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("iiisss", $siswa_id, $perusahaan_id, $pembimbing_id, $tgl_mulai, $tgl_selesai, $status);
        $stmt->execute();
        $msg = 'Penempatan berhasil ditambahkan.';
    } elseif ($action === 'edit_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE penempatan SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $msg = 'Status berhasil diubah.';
    } elseif ($action === 'hapus') {
        $id = (int)$_POST['id'];
        $conn->query("DELETE FROM penempatan WHERE id = $id");
        $msg = 'Data dihapus.';
    }
}

$filter_status = $_GET['status'] ?? '';
$where = $filter_status ? "WHERE pn.status = '".$conn->real_escape_string($filter_status)."'" : "";
$list = $conn->query("SELECT pn.*, s.nama as siswa, s.nis, pr.nama as perusahaan, pb.nama as pembimbing 
    FROM penempatan pn 
    JOIN siswa s ON pn.siswa_id = s.id 
    JOIN perusahaan pr ON pn.perusahaan_id = pr.id 
    JOIN pembimbing pb ON pn.pembimbing_id = pb.id 
    $where ORDER BY pn.tgl_mulai DESC");

$siswa_opts = $conn->query("SELECT id, nama, nis FROM siswa ORDER BY nama");
$perusahaan_opts = $conn->query("SELECT id, nama FROM perusahaan ORDER BY nama");
$pembimbing_opts = $conn->query("SELECT id, nama FROM pembimbing ORDER BY nama");

$pageTitle = 'Penempatan PKL';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-geo-alt"></i> Penempatan PKL</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg"></i> Tambah Penempatan</button>
    </div>
    <?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Belum Mulai" <?= $filter_status==='Belum Mulai'?'selected':'' ?>>Belum Mulai</option>
                    <option value="Aktif" <?= $filter_status==='Aktif'?'selected':'' ?>>Aktif</option>
                    <option value="Selesai" <?= $filter_status==='Selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>
        </form>
    </div></div>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Siswa</th><th>Perusahaan</th><th>Pembimbing</th><th>Tgl Mulai</th><th>Tgl Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php while ($r = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['siswa']) ?> <small class="text-muted">(<?= $r['nis'] ?>)</small></td>
                    <td><?= htmlspecialchars($r['perusahaan']) ?></td>
                    <td><?= htmlspecialchars($r['pembimbing']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['tgl_mulai'])) ?></td>
                    <td><?= $r['tgl_selesai'] ? date('d/m/Y', strtotime($r['tgl_selesai'])) : '-' ?></td>
                    <td><span class="badge bg-<?= $r['status']==='Aktif'?'success':($r['status']==='Selesai'?'primary':'secondary') ?>"><?= $r['status'] ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#status<?= $r['id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Yakin?')"><input type="hidden" name="action" value="hapus"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <div class="modal fade" id="status<?= $r['id'] ?>"><div class="modal-dialog"><div class="modal-content">
                    <form method="POST"><input type="hidden" name="action" value="edit_status"><input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <div class="modal-header"><h5 class="modal-title">Ubah Status</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <select name="status" class="form-select">
                            <option value="Belum Mulai" <?= $r['status']==='Belum Mulai'?'selected':'' ?>>Belum Mulai</option>
                            <option value="Aktif" <?= $r['status']==='Aktif'?'selected':'' ?>>Aktif</option>
                            <option value="Selesai" <?= $r['status']==='Selesai'?'selected':'' ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                    </form>
                </div></div></div>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="tambah">
    <div class="modal-header"><h5 class="modal-title">Tambah Penempatan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-2"><label>Siswa *</label><select name="siswa_id" class="form-select" required>
            <option value="">Pilih Siswa</option>
            <?php while ($s = $siswa_opts->fetch_assoc()): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?> (<?= $s['nis'] ?>)</option><?php endwhile; ?>
        </select></div>
        <div class="mb-2"><label>Perusahaan *</label><select name="perusahaan_id" class="form-select" required>
            <option value="">Pilih Perusahaan</option>
            <?php while ($p = $perusahaan_opts->fetch_assoc()): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option><?php endwhile; ?>
        </select></div>
        <div class="mb-2"><label>Pembimbing *</label><select name="pembimbing_id" class="form-select" required>
            <option value="">Pilih Pembimbing</option>
            <?php while ($b = $pembimbing_opts->fetch_assoc()): ?><option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nama']) ?></option><?php endwhile; ?>
        </select></div>
        <div class="mb-2"><label>Tanggal Mulai *</label><input type="date" name="tgl_mulai" class="form-control" required></div>
        <div class="mb-2"><label>Tanggal Selesai</label><input type="date" name="tgl_selesai" class="form-control"></div>
        <div class="mb-2"><label>Status</label><select name="status" class="form-select">
            <option value="Belum Mulai">Belum Mulai</option>
            <option value="Aktif">Aktif</option>
            <option value="Selesai">Selesai</option>
        </select></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
