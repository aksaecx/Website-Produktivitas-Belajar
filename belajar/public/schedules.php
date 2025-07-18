<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require '../includes/database.php';

$user_id = $_SESSION['user_id'];

// Tambah Jadwal Baru
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $start_time  = $_POST['start_time'];
    $end_time    = $_POST['end_time'];
    $status      = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO schedules (user_id, title, description, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $start_time, $end_time, $status]);
}

// Ambil Jadwal
$stmt = $pdo->prepare("SELECT * FROM schedules WHERE user_id = ? ORDER BY start_time ASC");
$stmt->execute([$user_id]);
$schedules = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal</title>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="blur-container">
        <h4 class="mb-4 text-center">MANAJEMEN JADWAL BELAJAR</h4>

        <form action="" method="POST" class="mb-5 bg-white bg-opacity-75 p-4 rounded shadow-sm">
            <h5 class="mb-3">Tambah Jadwal Baru</h5>
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Judul Jadwal" required>
            </div>
            <div class="mb-3">
                <textarea name="description" class="form-control" placeholder="Deskripsi (opsional)"></textarea>
            </div>
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Mulai:</label>
                    <input type="datetime-local" name="start_time" class="form-control" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Selesai:</label>
                    <input type="datetime-local" name="end_time" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <select name="status" class="form-select">
                    <option value="scheduled">Terjadwal</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
        </form>

        <h5 class="mb-3">Daftar Jadwal Anda</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped rounded-3 overflow-hidden">
                <thead class="table-light text-center">
                    <tr>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($schedules as $sch): ?>
                    <tr>
                        <td><?= htmlspecialchars($sch['title']) ?></td>
                        <td><?= htmlspecialchars($sch['description']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($sch['start_time'])) ?></td>
                        <td><?= date('d M Y H:i', strtotime($sch['end_time'])) ?></td>
                        <td>
                            <?php
                                $badge = match ($sch['status']) {
                                    'scheduled' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= ucfirst($sch['status']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
