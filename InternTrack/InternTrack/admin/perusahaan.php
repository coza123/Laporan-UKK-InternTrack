<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $nama = trim($_POST['nama'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $bidang = trim($_POST['bidang'] ?? '');
        $kontak = trim($_POST['kontak'] ?? '');
        if (empty($nama)) {
            $msg = 'Nama perusahaan wajib diisi.'; $msgType = 'danger';
        } else {
            $stmt = $conn->prepare("INSERT INTO perusahaan (nama, alamat, bidang, kontak) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $nama, $alamat, $bidang, $kontak);
            $stmt->execute();
            $msg = 'Perusahaan berhasil ditambahkan.';
        }
    } elseif ($action === 'edit') {
        $id = (int)$_POST['id'];
        $nama = trim($_POST['nama'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $bidang = trim($_POST['bidang'] ?? '');
        $kontak = trim($_POST['kontak'] ?? '');
        $stmt = $conn->prepare("UPDATE perusahaan SET nama=?, alamat=?, bidang=?, kontak=? WHERE id=?");
        $stmt->bind_param("ssssi", $nama, $alamat, $bidang, $kontak, $id);
        $stmt->execute();
        $msg = 'Data berhasil diupdate.';
    } elseif ($action === 'hapus') {
        $id = (int)$_POST['id'];
        $conn->query("DELETE FROM perusahaan WHERE id = $id");
        $msg = 'Data berhasil dihapus.';
    }
}

$search = trim($_GET['q'] ?? '');
$where = $search ? "WHERE nama LIKE '%".$conn->real_escape_string($search)."%' OR bidang LIKE '%".$conn->real_escape_string($search)."%'" : "";
$list = $conn->query("SELECT * FROM perusahaan $where ORDER BY nama");

$pageTitle = 'Data Perusahaan';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-building"></i> Data Perusahaan</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg"></i> Tambah</button>
    </div>
    <?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6"><input type="text" name="q" class="form-control" placeholder="Cari nama / bidang..." value="<?= htmlspecialchars($search) ?>"></div>
            <div class="col-md-2"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Cari</button></div>
        </form>
    </div></div>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nama</th><th>Bidang</th><th>Kontak</th><th>Alamat</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php while ($p = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nama']) ?></td>
                    <td><?= htmlspecialchars($p['bidang'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['kontak'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($p['alamat'] ?? '-', 0, 40, '...')) ?></td>
                    <td>
                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detail<?= $p['id'] ?>"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $p['id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Yakin?')"><input type="hidden" name="action" value="hapus"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <div class="modal fade" id="detail<?= $p['id'] ?>"><div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Detail Perusahaan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <p><strong>Nama:</strong> <?= htmlspecialchars($p['nama']) ?></p>
                        <p><strong>Bidang:</strong> <?= htmlspecialchars($p['bidang'] ?? '-') ?></p>
                        <p><strong>Kontak:</strong> <?= htmlspecialchars($p['kontak'] ?? '-') ?></p>
                        <p><strong>Alamat:</strong> <?= htmlspecialchars($p['alamat'] ?? '-') ?></p>
                    </div>
                </div></div></div>
                <div class="modal fade" id="edit<?= $p['id'] ?>"><div class="modal-dialog"><div class="modal-content">
                    <form method="POST"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <div class="modal-header"><h5 class="modal-title">Edit Perusahaan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-2"><label>Nama *</label><input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($p['nama']) ?>"></div>
                        <div class="mb-2"><label>Bidang</label><input type="text" name="bidang" class="form-control" value="<?= htmlspecialchars($p['bidang'] ?? '') ?>"></div>
                        <div class="mb-2"><label>Kontak</label><input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($p['kontak'] ?? '') ?>"></div>
                        <div class="mb-2"><label>Alamat</label><textarea name="alamat" class="form-control"><?= htmlspecialchars($p['alamat'] ?? '') ?></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
                    </form>
                </div></div></div>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="tambah">
    <div class="modal-header"><h5 class="modal-title">Tambah Perusahaan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-2"><label>Nama *</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-2"><label>Bidang</label><input type="text" name="bidang" class="form-control"></div>
        <div class="mb-2"><label>Kontak</label><input type="text" name="kontak" class="form-control"></div>
        <div class="mb-2"><label>Alamat</label><textarea name="alamat" class="form-control"></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
