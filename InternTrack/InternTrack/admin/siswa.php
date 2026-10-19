<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'pembimbing']);

$conn = getConnection();
$msg = '';
$msgType = 'success';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'tambah') {
        $nis = trim($_POST['nis'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $kelas = trim($_POST['kelas'] ?? '');
        $jurusan = trim($_POST['jurusan'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? 'siswa123';

        if (empty($nis) || empty($nama) || empty($kelas) || empty($jurusan) || empty($username)) {
            $msg = 'NIS, Nama, Kelas, Jurusan, dan Username wajib diisi.';
            $msgType = 'danger';
        } else {
            // Check duplicate NIS
            $check = $conn->prepare("SELECT id FROM siswa WHERE nis = ?");
            $check->bind_param("s", $nis);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $msg = 'NIS sudah terdaftar.';
                $msgType = 'danger';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $conn->begin_transaction();
                try {
                    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'siswa')");
                    $stmt->bind_param("ss", $username, $hash);
                    $stmt->execute();
                    $user_id = $conn->insert_id;
                    
                    $stmt = $conn->prepare("INSERT INTO siswa (user_id, nis, nama, kelas, jurusan, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issssss", $user_id, $nis, $nama, $kelas, $jurusan, $no_hp, $alamat);
                    $stmt->execute();
                    $siswa_id = $conn->insert_id;
                    
                    $conn->query("UPDATE users SET related_id = $siswa_id WHERE id = $user_id");
                    $conn->commit();
                    $msg = 'Data siswa berhasil ditambahkan.';
                } catch (Exception $e) {
                    $conn->rollback();
                    $msg = 'Gagal menambah data: ' . $e->getMessage();
                    $msgType = 'danger';
                }
            }
        }
    } elseif ($action === 'edit') {
        $id = (int)$_POST['id'];
        $nis = trim($_POST['nis'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $kelas = trim($_POST['kelas'] ?? '');
        $jurusan = trim($_POST['jurusan'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');

        if (empty($nis) || empty($nama)) {
            $msg = 'NIS dan Nama wajib diisi.';
            $msgType = 'danger';
        } else {
            $check = $conn->prepare("SELECT id FROM siswa WHERE nis = ? AND id != ?");
            $check->bind_param("si", $nis, $id);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $msg = 'NIS sudah digunakan siswa lain.';
                $msgType = 'danger';
            } else {
                $stmt = $conn->prepare("UPDATE siswa SET nis=?, nama=?, kelas=?, jurusan=?, no_hp=?, alamat=? WHERE id=?");
                $stmt->bind_param("ssssssi", $nis, $nama, $kelas, $jurusan, $no_hp, $alamat, $id);
                $stmt->execute();
                $msg = 'Data siswa berhasil diupdate.';
            }
        }
    } elseif ($action === 'hapus') {
        $id = (int)$_POST['id'];
        $row = $conn->query("SELECT user_id FROM siswa WHERE id = $id")->fetch_assoc();
        if ($row) {
            $conn->query("DELETE FROM siswa WHERE id = $id");
            if ($row['user_id']) $conn->query("DELETE FROM users WHERE id = " . (int)$row['user_id']);
            $msg = 'Data siswa berhasil dihapus.';
        }
    }
}

// Search & filter
$search = trim($_GET['q'] ?? '');
$filter_kelas = $_GET['kelas'] ?? '';
$where = "1=1";
if ($search) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (s.nama LIKE '%$s%' OR s.nis LIKE '%$s%')";
}
if ($filter_kelas) {
    $k = $conn->real_escape_string($filter_kelas);
    $where .= " AND s.kelas = '$k'";
}

$siswa_list = $conn->query("SELECT s.* FROM siswa s WHERE $where ORDER BY s.nama");
$siswa_rows = $siswa_list->fetch_all(MYSQLI_ASSOC);
$kelas_list = $conn->query("SELECT DISTINCT kelas FROM siswa ORDER BY kelas");

$pageTitle = 'Data Siswa';
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-people"></i> Data Siswa PKL</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show"><?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Cari nama / NIS..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php while ($k = $kelas_list->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($k['kelas']) ?>" <?= $filter_kelas === $k['kelas'] ? 'selected' : '' ?>><?= htmlspecialchars($k['kelas']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa_rows as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['nis']) ?></td>
                            <td><?= htmlspecialchars($s['nama']) ?></td>
                            <td><?= htmlspecialchars($s['kelas']) ?></td>
                            <td><?= htmlspecialchars($s['jurusan']) ?></td>
                            <td><?= htmlspecialchars($s['no_hp'] ?? '-') ?></td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $s['id'] ?>"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $s['id'] ?>"><i class="bi bi-pencil"></i></button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php foreach ($siswa_rows as $s): ?>
    <div class="modal fade" id="modalDetail<?= $s['id'] ?>" tabindex="-1" aria-labelledby="modalDetailLabel<?= $s['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel<?= $s['id'] ?>">Detail Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr><th>NIS</th><td><?= htmlspecialchars($s['nis']) ?></td></tr>
                        <tr><th>Nama</th><td><?= htmlspecialchars($s['nama']) ?></td></tr>
                        <tr><th>Kelas</th><td><?= htmlspecialchars($s['kelas']) ?></td></tr>
                        <tr><th>Jurusan</th><td><?= htmlspecialchars($s['jurusan']) ?></td></tr>
                        <tr><th>No. HP</th><td><?= htmlspecialchars($s['no_hp'] ?? '-') ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($s['alamat'] ?? '-') ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit<?= $s['id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $s['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel<?= $s['id'] ?>">Edit Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2"><label class="form-label">NIS *</label><input type="text" name="nis" class="form-control" required value="<?= htmlspecialchars($s['nis']) ?>"></div>
                        <div class="mb-2"><label class="form-label">Nama *</label><input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($s['nama']) ?>"></div>
                        <div class="mb-2"><label class="form-label">Kelas *</label><input type="text" name="kelas" class="form-control" required value="<?= htmlspecialchars($s['kelas']) ?>"></div>
                        <div class="mb-2"><label class="form-label">Jurusan *</label><input type="text" name="jurusan" class="form-control" required value="<?= htmlspecialchars($s['jurusan']) ?>"></div>
                        <div class="mb-2"><label class="form-label">No. HP</label><input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($s['no_hp'] ?? '') ?>"></div>
                        <div class="mb-2"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"><?= htmlspecialchars($s['alamat'] ?? '') ?></textarea></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="tambah">
                <div class="modal-header"><h5 class="modal-title">Tambah Siswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-2"><label class="form-label">NIS *</label><input type="text" name="nis" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label">Nama *</label><input type="text" name="nama" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label">Kelas *</label><input type="text" name="kelas" class="form-control" required placeholder="XII RPL 1"></div>
                    <div class="mb-2"><label class="form-label">Jurusan *</label><input type="text" name="jurusan" class="form-control" required value="Rekayasa Perangkat Lunak"></div>
                    <div class="mb-2"><label class="form-label">No. HP</label><input type="text" name="no_hp" class="form-control"></div>
                    <div class="mb-2"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"></textarea></div>
                    <hr>
                    <div class="mb-2"><label class="form-label">Username Login *</label><input type="text" name="username" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label">Password</label><input type="text" name="password" class="form-control" value="siswa123"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
