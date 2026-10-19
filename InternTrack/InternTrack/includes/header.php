<?php
if (!isset($pageTitle)) $pageTitle = 'InternTrack';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - InternTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars(appUrl('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<?php if ($user): ?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= htmlspecialchars(appUrl('index.php')) ?>">
            <i class="bi bi-briefcase-fill me-2"></i>InternTrack
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($user['role'] === 'admin' || $user['role'] === 'pembimbing'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/dashboard.php')) ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/siswa.php')) ?>"><i class="bi bi-people"></i> Siswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/perusahaan.php')) ?>"><i class="bi bi-building"></i> Perusahaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/pembimbing.php')) ?>"><i class="bi bi-person-badge"></i> Pembimbing</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/penempatan.php')) ?>"><i class="bi bi-geo-alt"></i> Penempatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/jurnal.php')) ?>"><i class="bi bi-journal-text"></i> Jurnal</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/absensi.php')) ?>"><i class="bi bi-calendar-check"></i> Absensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('admin/monitoring.php')) ?>"><i class="bi bi-clipboard-check"></i> Monitoring</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('laporan/index.php')) ?>"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('siswa/dashboard.php')) ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('siswa/profil.php')) ?>"><i class="bi bi-person"></i> Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('siswa/jurnal.php')) ?>"><i class="bi bi-journal-text"></i> Jurnal</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('siswa/absensi.php')) ?>"><i class="bi bi-calendar-check"></i> Absensi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars(appUrl('siswa/monitoring.php')) ?>"><i class="bi bi-clipboard-check"></i> Monitoring</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($user['nama']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small">Role: <?= ucfirst($user['role']) ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= htmlspecialchars(appUrl('auth/logout.php')) ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>
<main class="container-fluid py-4">
