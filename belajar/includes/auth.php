<?php
// File: includes/auth.php
session_start();
require 'database.php';

// Fungsi untuk mengecek apakah pengguna sudah login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk mendapatkan informasi pengguna yang sedang login
function get_logged_in_user($pdo) {
    if (!is_logged_in()) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Fungsi untuk melakukan login
function login($email, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }

    return false;
}

// Fungsi untuk logout
function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}
