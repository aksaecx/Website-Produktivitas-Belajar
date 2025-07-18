<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar Produktif</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <?php 
    $current_page = basename($_SERVER['PHP_SELF']);
    switch($current_page) {
        case 'dashboard.php':
            echo '<link rel="stylesheet" href="css/dashboard.css">';
            break;
        case 'schedules.php':
            echo '<link rel="stylesheet" href="css/schedules.css">';
            break;
        case 'tasks.php':
            echo '<link rel="stylesheet" href="css/tasks.css">';
            break;
        case 'resources.php':
            echo '<link rel="stylesheet" href="css/resources.css">';
            break;
        case 'profile.php':
            echo '<link rel="stylesheet" href="css/profile.css">';
            break;
    }
    ?>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <span class="navbar-brand"><b>BELAJAR PRODUKTIF</span>
    <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="schedules.php">Jadwal</a></li>
        <li class="nav-item"><a class="nav-link" href="tasks.php">Tugas</a></li>
        <li class="nav-item"><a class="nav-link" href="resources.php">Materi</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link logout" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<div class="container py-5">