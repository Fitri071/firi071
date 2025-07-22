<?php
require 'koneksi.php';
require 'helpers.php';
redirect_if_not_logged_in();

// Buat folder 'uploads' jika belum ada
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Sistem Mahasiswa</a>
        <div class="d-flex">
            <span class="navbar-text me-3">
                Halo, <?= htmlspecialchars($_SESSION['user_name']); ?>
            </span>
            <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="text-center mt-4">
    <?php
    $userFoto = isset($_SESSION['user_foto']) ? $_SESSION['user_foto'] : 'uploads/u_687f3347963ec2.62781913.jpg';
    $fotoPath = __DIR__ . '/uploads/' . $userFoto;
    ?>

    <?php if (!empty($userFoto) && file_exists($fotoPath)): ?>
        <img src="uploads/<?= htmlspecialchars($userFoto); ?>" class="rounded-circle mb-3" width="140" height="140" alt="Foto Profil">
    <?php else: ?>
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']); ?>&size=140" class="rounded-circle mb-3" alt="Avatar">
    <?php endif; ?>

    <h1 class="mb-3">Selamat datang, <?= htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p class="lead">Anda berhasil login. Silakan kelola data mahasiswa Anda.</p>

    <a href="crud_mahasiswa.php" class="btn btn-success mt-4">Kelola Data Mahasiswa</a>
</div>

</body>
</html>
