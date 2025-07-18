<?php
session_start();
require '../includes/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BelajarProduktif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
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

        .login-container {
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
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ffdddd;
            background-color: #cc0000;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .footer-text {
            text-align: center;
            margin-top: 1rem;
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
            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Masuk ke BelajarProduktif</h2>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Alamat Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Kata Sandi" required>
            </div>
            <button type="submit">Masuk</button>
        </form>

        <p class="footer-text">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
