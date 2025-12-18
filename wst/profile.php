<?php
session_start();
require_once 'db.php';

// 1. Perbaikan Blok Cek Login (Baris 5-9)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} // Tambahkan kurung tutup ini

$error_pass = '';
$success_pass = '';
$error_edit = '';   
$success_edit = '';

// Logika Ganti Password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_pass = "Semua kolom wajib diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error_pass = "Password baru dan konfirmasi tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $error_pass = "Password baru minimal 6 karakter.";
    } else {
        $sql_fetch_pass = "SELECT password FROM users WHERE id = ?";
        $stmt_fetch_pass = $conn->prepare($sql_fetch_pass);
        $stmt_fetch_pass->bind_param("i", $user_id);
        $stmt_fetch_pass->execute();
        $result_fetch_pass = $stmt_fetch_pass->get_result();
        
        if ($result_fetch_pass->num_rows > 0) {
            $user = $result_fetch_pass->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_update_pass = "UPDATE users SET password = ? WHERE id = ?";
                $stmt_update_pass = $conn->prepare($sql_update_pass);
                $stmt_update_pass->bind_param("si", $new_hashed_password, $user_id);
                
                if ($stmt_update_pass->execute()) {
                    $success_pass = "Password berhasil diubah!";
                } else {
                    $error_pass = "Gagal mengubah password.";
                }
                $stmt_update_pass->close();
            } else {
                $error_pass = "Password lama salah!";
            }
        }
        $stmt_fetch_pass->close();
    }
}

// Data Pengguna
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_email = ''; 
$riwayat_booking = []; 

$sql_user = "SELECT email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_email = $user_data['email'];
}
$stmt_user->close();

// Riwayat Booking - Menggunakan email sesuai diskusi sebelumnya
$sql_booking = "SELECT id, departure_date, status FROM bookings WHERE email = ? ORDER BY departure_date DESC";
$stmt_booking = $conn->prepare($sql_booking);
$stmt_booking->bind_param("s", $user_email);
$stmt_booking->execute();
$result_booking = $stmt_booking->get_result();

if ($result_booking) {
    while ($row = $result_booking->fetch_assoc()) {
        $riwayat_booking[] = $row;
    }
}
$stmt_booking->close();

$active_menu = isset($_GET['menu']) ? $_GET['menu'] : 'profil'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Saya - <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="login-register-style.css" />
    <style>
        .profile-container { min-height: 80vh; padding: 50px 0; background: #f8fafc; }
        .profile-grid { display: grid; grid-template-columns: 250px 1fr; gap: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .sidebar { background: #f1f5f9; padding: 30px 0; border-right: 1px solid #e2e8f0; }
        .sidebar h3 { padding: 0 30px 15px; color: #1e3a8a; border-bottom: 1px solid #cbd5e1; margin-bottom: 20px; }
        .sidebar a { display: block; padding: 12px 30px; text-decoration: none; color: #475569; font-weight: 500; transition: all 0.2s ease; }
        .sidebar a:hover { background: #e2e8f0; color: #1d4ed8; }
        .sidebar a.active { background: #1d4ed8; color: white; font-weight: 700; border-left: 4px solid #fcd34d; }
        .content { padding: 30px; }
        .content h2 { color: #1e3a8a; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .profile-info p { margin-bottom: 15px; font-size: 1rem; color: #334155; }
        .profile-info strong { display: inline-block; width: 120px; color: #111; }
        .btn-action { display: inline-block; padding: 8px 15px; background: #1d4ed8; color: white; text-decoration: none; border-radius: 8px; margin-top: 15px; }
        .booking-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .booking-table th, .booking-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        @media (max-width: 768px) { .profile-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
  <header class="navbar">
    <div class="container">
      <div class="logo"><div class="logo-box">GP</div><div><h1>Booking Online Pesagi</h1><p>Petualangan asik dan aman</p></div></div>
      <nav>
        <a href="index.php">Home</a>
        <a href="objek-wisata.php">Objek Wisata</a>
        <a href="booking.php">Booking</a>
        <a href="sop.php">S O P</a>
        <a href="cek-kuota.php">Cek Kuota</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php" class="active">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php" style="color: #dc2626;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <section class="profile-container">
    <div class="container">
        <div class="profile-grid">
            <div class="sidebar">
                <h3>Akun Saya</h3>
                <a href="profile.php?menu=profil" class="<?= ($active_menu == 'profil') ? 'active' : '' ?>">Profil & Pengaturan</a>
                <a href="profile.php?menu=riwayat" class="<?= ($active_menu == 'riwayat') ? 'active' : '' ?>">Riwayat Booking</a>
                <a href="profile.php?menu=password" class="<?= ($active_menu == 'password') ? 'active' : '' ?>">Ubah Password</a>
                <a href="logout.php" style="color: #dc2626;">Logout</a>
            </div>
            
            <div class="content">
                <?php if ($active_menu == 'profil'): ?>
                    <h2>Profil & Pengaturan Akun</h2>
                    <div class="profile-info">
                        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></p>
                        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
                    </div>
                    <a href="profile.php?menu=edit" class="btn-action">Ubah Nama/Email</a>
                
                <?php elseif ($active_menu == 'riwayat'): ?>
                    <h2>Riwayat Booking Saya</h2>
                    <?php if (empty($riwayat_booking)): ?>
                        <div class="alert alert-info">Anda belum memiliki riwayat booking.</div>
                    <?php else: ?>
                        <table class="booking-table">
                            <thead><tr><th>ID</th><th>Tanggal Keberangkatan</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($riwayat_booking as $b): ?>
                                    <tr>
                                        <td>#<?= $b['id'] ?></td>
                                        <td><?= date('d M Y', strtotime($b['departure_date'])) ?></td>
                                        <td><?= htmlspecialchars($b['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                <?php elseif ($active_menu == 'password'): ?>
                    <h2>Ubah Password</h2>
                    <?php if ($error_pass): ?><div class="alert alert-error"><?= $error_pass ?></div><?php endif; ?>
                    <?php if ($success_pass): ?><div class="alert alert-success"><?= $success_pass ?></div><?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="change_password">
                        <div class="form-group"><label>Password Lama</label><input type="password" name="current_password" class="form-control" required></div>
                        <div class="form-group"><label>Password Baru</label><input type="password" name="new_password" class="form-control" required></div>
                        <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="confirm_password" class="form-control" required></div>
                        <button type="submit" class="btn-login">Simpan Password</button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
  </section>
</body>
</html>