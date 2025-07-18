<?php
session_start();
require '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$username = $stmt->fetchColumn();

$tugas_stmt = $pdo->prepare("SELECT status, COUNT(*) as total FROM tasks WHERE user_id = ? GROUP BY status");
$tugas_stmt->execute([$user_id]);
$tugas_data = $tugas_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$jadwal_stmt = $pdo->prepare("SELECT title, start_time FROM schedules WHERE user_id = ? AND start_time >= NOW() ORDER BY start_time ASC LIMIT 3");
$jadwal_stmt->execute([$user_id]);
$jadwal_terdekat = $jadwal_stmt->fetchAll();

$materi_stmt = $pdo->prepare("SELECT COUNT(*) FROM resources WHERE user_id = ?");
$materi_stmt->execute([$user_id]);
$total_materi = $materi_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Belajar</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    

<?php include '../includes/header.php'; ?>

<div class="main-container">
    <h2>Selamat Datang, <?= htmlspecialchars($username) ?>!</h2>
    <p>Berikut ringkasan aktivitas belajarmu:</p>

    <div class="card-container">
        <div class="card bg-success">
            <h3>Tugas Selesai</h3>
            <p class="angka"><?= $tugas_data['completed'] ?? 0 ?></p>
        </div>
        <div class="card bg-warning">
            <h3>Tugas Berjalan</h3>
            <p class="angka"><?= $tugas_data['in_progress'] ?? 0 ?></p>
        </div>
        <div class="card bg-danger">
            <h3>Tugas Belum Selesai</h3>
            <p class="angka"><?= $tugas_data['pending'] ?? 0 ?></p>
        </div>
    </div>

    <div class="mt-5">
        <h4>Jadwal Terdekat</h4>
        <?php if (count($jadwal_terdekat) > 0): ?>
            <div class="jadwal-list">
                <?php foreach ($jadwal_terdekat as $jadwal): ?>
                    <div class="jadwal-item">
                        <span><?= htmlspecialchars($jadwal['title']) ?></span>
                        <span class="text-muted"><?= date('d M Y H:i', strtotime($jadwal['start_time'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada jadwal dalam waktu dekat.</p>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <h4>Total Materi Belajar</h4>
        <p class="fs-4">Kamu telah mengunggah <strong><?= $total_materi ?></strong> sumber belajar.</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
