
<?php
/***** CRUD MAHASISWA (PDO + Bootstrap) *****/
require 'koneksi.php';      // koneksi $pdo + session_start()
require 'helpers.php';     // fungsi redirect_if_not_logged_in()
redirect_if_not_logged_in();

/* ========== CREATE ========== */
if (isset($_POST['tambah'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO mahasiswa (nim, nama, jurusan, alamat) VALUES (:nim,:nama,:jurusan,:alamat)"
    );
    $stmt->execute([
        ':nim'     => $_POST['nim'],
        ':nama'    => $_POST['nama'],
        ':jurusan' => $_POST['jurusan'],
        ':alamat'  => $_POST['alamat']
    ]);
    header('Location: crud_mahasiswa.php');
    exit;
}

/* ========== UPDATE ========== */
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare(
        "UPDATE mahasiswa SET nim=:nim, nama=:nama, jurusan=:jurusan, alamat=:alamat
         WHERE id_mahasiswa=:id"
    );
    $stmt->execute([
        ':nim'     => $_POST['nim'],
        ':nama'    => $_POST['nama'],
        ':jurusan' => $_POST['jurusan'],
        ':alamat'  => $_POST['alamat'],
        ':id'      => $_POST['id_mahasiswa']
    ]);
    header('Location: crud_mahasiswa.php');
    exit;
}

/* ========== DELETE ========== */
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM mahasiswa WHERE id_mahasiswa=?")
        ->execute([$_GET['delete']]);
    header('Location: crud_mahasiswa.php');
    exit;
}

/* ========== READ ========== */
$rows = $pdo->query("SELECT * FROM mahasiswa ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">Dashboard</a>
        <span class="text-white me-3">Halo, <?= $_SESSION['user_name']; ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container py-4">
    <h3 class="mb-4 text-center">Data Mahasiswa</h3>

    <!-- ===== Form Tambah / Edit ===== -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <?php
            $edit = null;
            if (isset($_GET['edit'])) {
                $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa=?");
                $stmt->execute([$_GET['edit']]);
                $edit = $stmt->fetch();
            }
            ?>
            <h5 class="card-title"><?= $edit ? 'Edit' : 'Tambah'; ?> Mahasiswa</h5>
            <form method="POST">
                <?php if ($edit): ?>
                    <input type="hidden" name="id_mahasiswa" value="<?= $edit['id_mahasiswa']; ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="nim" class="form-control" placeholder="NIM"
                               required value="<?= $edit['nim'] ?? '' ?>" <?= $edit ? 'readonly' : ''; ?>>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap"
                               required value="<?= $edit['nama'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="jurusan" class="form-control" placeholder="Jurusan"
                               required value="<?= $edit['jurusan'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat"
                               required value="<?= $edit['alamat'] ?? '' ?>">
                    </div>
                </div>

                <button type="submit" name="<?= $edit ? 'update' : 'tambah'; ?>"
                        class="btn btn-primary mt-3"><?= $edit ? 'Update' : 'Tambah'; ?></button>
                <?php if ($edit): ?>
                    <a href="crud_mahasiswa.php" class="btn btn-secondary mt-3 ms-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- ===== Table ===== -->
    <div class="table-responsive shadow">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Alamat</th>
                <th>Waktu Dibuat</th>
                <th width="130">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($rows): foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['nim']); ?></td>
                    <td><?= htmlspecialchars($r['nama']); ?></td>
                    <td><?= htmlspecialchars($r['jurusan']); ?></td>
                    <td><?= htmlspecialchars($r['alamat']); ?></td>
                    <td><?= $r['created_at']; ?></td>
                    <td>
                        <a href="crud_mahasiswa.php?edit=<?= $r['id_mahasiswa']; ?>"
                           class="btn btn-warning btn-sm">Edit</a>
                        <a href="crud_mahasiswa.php?delete=<?= $r['id_mahasiswa']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center p-4">Belum ada data.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
