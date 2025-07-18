<?php
session_start();
require '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// === UPLOAD FILE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $url = $_POST['url'];

    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = basename($_FILES["file"]["name"]);
        $target_path = "../uploads/" . time() . "_" . $file_name;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_path)) {
            $file_path = $target_path;
        } else {
            $message = "Gagal mengunggah file.";
        }
    }

    $stmt = $pdo->prepare("INSERT INTO resources (user_id, title, description, file_path, url, category, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $title, $description, $file_path, $url, $category]);
    $message = "Sumber belajar berhasil ditambahkan.";
}

// === HAPUS FILE ===
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("SELECT file_path FROM resources WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $resource = $stmt->fetch();
    if ($resource && $resource['file_path'] && file_exists($resource['file_path'])) {
        unlink($resource['file_path']);
    }

    $stmt = $pdo->prepare("DELETE FROM resources WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    $message = "Sumber belajar berhasil dihapus.";
}

// === AMBIL SEMUA DATA ===
$stmt = $pdo->prepare("SELECT * FROM resources WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$user_id]);
$resources = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sumber Belajar</title>
    <link rel="stylesheet" href="css/resources.css">

</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="content-wrapper">
        <h4 class="mb-4">SUMBER BELAJAR</h4>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mb-4 bg-light p-4 rounded shadow-sm">
            <h5 class="mb-3">Tambah Materi Baru</h5>
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Judul Materi" required>
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" placeholder="Deskripsi Materi" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <input type="text" name="category" class="form-control" placeholder="Kategori (contoh: Matematika)" required>
            </div>
            <div class="mb-3">
                <input type="url" name="url" class="form-control" placeholder="Link eksternal (opsional)">
            </div>
            <div class="mb-3">
                <input type="file" name="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Sumber Belajar</button>
        </form>

        <h5 class="mb-3">Daftar Sumber Belajar</h5>
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>File</th>
                    <th>Link</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($resources as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['category']) ?></td>
                    <td>
                        <?php if ($r['file_path']): ?>
                            <a href="<?= $r['file_path'] ?>" download>Unduh</a>
                        <?php else: ?> -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($r['url']): ?>
                            <a href="<?= $r['url'] ?>" target="_blank">Kunjungi</a>
                        <?php else: ?> -
                        <?php endif; ?>
                    </td>
                    <td><?= nl2br(htmlspecialchars($r['description'])) ?></td>
                    <td><?= date('d M Y H:i', strtotime($r['uploaded_at'])) ?></td>
                    <td>
                        <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
