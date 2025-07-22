<?php
require 'koneksi.php';
require 'helpers.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi
    $nama     = sanitize($_POST['nama'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';

    // Validasi dasar
    if ($nama === '')           $errors[] = 'Nama wajib diisi.';
    if ($username === '')       $errors[] = 'Username wajib diisi.';
    if (strlen($password) < 6)  $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok.';

    // Cek username sudah ada?
    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id_user FROM user WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) $errors[] = 'Username sudah dipakai, pilih yang lain.';
    }

    // Validasi & proses upload foto (opsional)
    $fotoName = null;
    if (!$errors && !empty($_FILES['foto']['name'])) {
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Format foto harus jpg, jpeg, png, atau gif.';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran foto maksimal 2 MB.';
        } else {
            $fotoName = uniqid('u_', true) . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/uploads/$fotoName");
        }
    }

    // Simpan ke DB
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO user (nama, username, password, foto) VALUES (?,?,?,?)");
        $stmt->execute([$nama, $username, $hash, $fotoName]);
        $_SESSION['success'] = 'Registrasi berhasil, silakan login.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="mb-4 text-center">Registrasi</h3>

                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required value="<?= $nama ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required value="<?= $username ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Foto (opsional)</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <button class="btn btn-primary w-100">Daftar</button>
                        <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
