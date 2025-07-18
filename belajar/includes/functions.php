<?php
// File: includes/functions.php

// Fungsi untuk membersihkan input dari pengguna
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Fungsi untuk memformat tanggal dan waktu ke format lokal (Indonesia)
function format_datetime($datetime) {
    return date('d M Y H:i', strtotime($datetime));
}

// Fungsi untuk memotong teks panjang
function truncate($text, $max = 100) {
    return strlen($text) > $max ? substr($text, 0, $max) . '...' : $text;
}

// Fungsi debug (jika diperlukan)
function debug($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
