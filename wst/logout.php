<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. Ambil data user berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // 2. Verifikasi Password
        if (password_verify($password, $user['password'])) {
            
            // 3. Simpan data ke SESSION (Sangat Penting untuk Admin & Profile)
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role']; // Ini yang menentukan Admin atau bukan

            // 4. Pengalihan Halaman (Redirect)
            if ($user['role'] === 'admin') {
                // Jika admin, kirim ke dashboard admin
                header("Location: admin-booking.php");
            } else {
                // Jika user biasa, kirim ke halaman utama
                header("Location: index.php");
            }
            exit();

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Pesagi</title>
    <link rel="stylesheet" href="style.css"> <style>
        .login-box { width: 300px; margin: 100px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .error-msg { color: red; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #d97706; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>