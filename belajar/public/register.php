<?php
require '../includes/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | BelajarProduktif</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: url('belajar.JPG') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        h2 {
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            background: rgba(255,255,255,0.8);
            transition: box-shadow 0.3s;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.5);
        }

        .password-requirements {
            font-size: 0.8rem;
            color: white;
            margin-top: 0.25rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 0.5rem;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: white;
        }

        .footer-text a {
            color:rgb(255, 0, 0);
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 500px) {
            .register-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Buat Akun Baru</h2>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Nama Pengguna" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Alamat Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Kata Sandi" required>
                <div class="password-requirements">Minimal 8 karakter</div>
            </div>
            <button type="submit">Daftar Sekarang</button>
        </form>

        <p class="footer-text">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </div>
</body>
</html>
