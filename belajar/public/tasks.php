<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../includes/database.php';
$user_id = $_SESSION['user_id'];

// Proses penambahan tugas
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $due_date    = $_POST['due_date'];
    $status      = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $due_date, $status]);
}

// Ambil semua tugas
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MANAJEMEN TUGAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="content-wrapper">
        <h4 class="mb-4">MANAJEMEN TUGAS</h4>

        <form action="" method="POST" class="mb-5 bg-light p-4 rounded shadow-sm">
            <h5 class="mb-3">Tambah Tugas Baru</h5>
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Judul Tugas" required>
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" placeholder="Deskripsi"></textarea>
            </div>
            <div class="mb-3">
                <label>Deadline:</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <select name="status" class="form-control">
                    <option value="pending">Belum Selesai</option>
                    <option value="in_progress">Sedang Berjalan</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Tambah Tugas</button>
        </form>

        <h5>Daftar Tugas</h5>
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= date('d M Y', strtotime($task['due_date'])) ?></td>
                    <td>
                        <?php
                        $badge = 'secondary';
                        if ($task['status'] === 'pending') $badge = 'danger';
                        elseif ($task['status'] === 'in_progress') $badge = 'warning';
                        elseif ($task['status'] === 'completed') $badge = 'success';
                        ?>
                        <span class="badge bg-<?= $badge ?>">
                            <?= ucwords(str_replace('_', ' ', $task['status'])) ?>
                        </span>
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
