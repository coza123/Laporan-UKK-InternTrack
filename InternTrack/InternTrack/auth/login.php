<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . appUrl('index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, username, password, role, related_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $nama = $user['username'];
            if ($user['role'] === 'siswa' && $user['related_id']) {
                $s = $conn->query("SELECT nama FROM siswa WHERE id = " . (int)$user['related_id'])->fetch_assoc();
                $nama = $s['nama'] ?? $nama;
            } elseif ($user['role'] === 'pembimbing' && $user['related_id']) {
                $p = $conn->query("SELECT nama FROM pembimbing WHERE id = " . (int)$user['related_id'])->fetch_assoc();
                $nama = $p['nama'] ?? $nama;
            } elseif ($user['role'] === 'admin') {
                $nama = 'Administrator';
            }
            loginUser($user, $nama);
            header('Location: ' . appUrl('index.php'));
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - InternTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars(appUrl('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="login-heading mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-briefcase-fill" aria-hidden="true"></i>
                    <h3 class="mb-0">InternTrack</h3>
                </div>
                <p class="text-muted mb-0 mt-2">Sistem Monitoring Kegiatan PKL</p>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
