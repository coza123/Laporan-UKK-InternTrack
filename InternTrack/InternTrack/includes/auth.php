<?php
session_start();

function appUrl($path = '') {
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $baseDirectory = preg_replace('~/(admin|siswa|laporan|auth)$~', '', $scriptDirectory);
    $baseDirectory = rtrim($baseDirectory, '/');

    return $baseDirectory . '/' . ltrim($path, '/');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . appUrl('auth/login.php'));
        exit;
    }
}

function requireRole($roles) {
    requireLogin();
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    if (!in_array($_SESSION['role'], $roles)) {
        header('Location: ' . appUrl('index.php'));
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'],
        'related_id' => $_SESSION['related_id'] ?? null,
        'nama' => $_SESSION['nama'] ?? $_SESSION['username']
    ];
}

function loginUser($user, $nama = null) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['related_id'] = $user['related_id'];
    $_SESSION['nama'] = $nama ?? $user['username'];
}

function logoutUser() {
    session_unset();
    session_destroy();
}

function getStatusKehadiran($persentase) {
    if ($persentase >= 90) return ['label' => 'Sangat Baik', 'class' => 'success'];
    if ($persentase >= 75) return ['label' => 'Baik', 'class' => 'primary'];
    if ($persentase >= 60) return ['label' => 'Perlu Perhatian', 'class' => 'warning'];
    return ['label' => 'Perlu Pembinaan', 'class' => 'danger'];
}

function hitungPersentaseKehadiran($siswa_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir
        FROM absensi WHERE siswa_id = ?");
    $stmt->bind_param("i", $siswa_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($result['total'] == 0) return 0;
    return round(($result['hadir'] / $result['total']) * 100, 1);
}
?>
