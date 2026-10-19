<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if (!isLoggedIn()) {
    header('Location: ' . appUrl('auth/login.php'));
    exit;
}

$role = $_SESSION['role'];
if ($role === 'admin' || $role === 'pembimbing') {
    header('Location: ' . appUrl('admin/dashboard.php'));
} else {
    header('Location: ' . appUrl('siswa/dashboard.php'));
}
exit;
?>
