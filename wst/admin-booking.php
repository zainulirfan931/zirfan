<?php
session_start();
// Pastikan hanya admin yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

// --- LOGIKA UPDATE STATUS (SETUJUI/BATAL) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $booking_id);
    if ($stmt->execute()) {
        $msg = "Status booking #$booking_id berhasil diperbarui!";
    }
    $stmt->close();
}

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM bookings WHERE id='$id'");
    header("Location: admin-booking.php?menu=bookings&status=deleted");
    exit();
}

// --- AMBIL DATA STATISTIK UNTUK DASHBOARD ---
$total_booking = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
$pending_booking = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status='pending'")->fetch_assoc()['total'];
$success_booking = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status='confirmed'")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'")->fetch_assoc()['total'];

// Ambil data bookings
$bookings_res = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
// Ambil data users
$users_res = $conn->query("SELECT id, username, email, role FROM users");

$active_menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Gunung Pesagi</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .btn-approve { background: #10b981; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .btn-cancel { background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><div class="logo">GP</div> <span>Admin</span></h2>
        </div>
        <nav class="sidebar-nav">
            <a href="admin-booking.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            <a href="admin-booking.php"><i class="fas fa-calendar-check"></i> <span>Kelola Booking</span></a>
    
            <a href="admin-manage.php?menu=destinations"><i class="fas fa-map-marked-alt"></i> <span>Kelola Wisata</span></a>
            <a href="admin-manage.php?menu=quota"><i class="fas fa-mountain"></i> <span>Atur Kuota</span></a>
    
            <a href="admin-booking.php?menu=users"><i class="fas fa-users"></i> <span>Pengguna</span></a>
            <a href="index.php"><i class="fas fa-external-link-alt"></i> <span>Lihat Web</span></a>
        </nav>
    </div>

    <div class="main-content">
        <header class="main-header">
            <h1>Panel Administrasi</h1>
            <div class="user-info">Halo, Admin <strong><?= $_SESSION['username'] ?></strong></div>
        </header>

        <div class="content-body">
            <?php if(isset($msg)): ?>
                <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><?= $msg ?></div>
            <?php endif; ?>

            <div class="tab-content <?= $active_menu == 'dashboard' ? 'active' : '' ?>">
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-icon" style="background: #e0e7ff; color: #4338ca;"><i class="fas fa-shopping-cart"></i></div>
                        <div class="card-info"><h3><?= $total_booking ?></h3><p>Total Pesanan</p></div>
                    </div>
                    <div class="card">
                        <div class="card-icon" style="background: #fef3c7; color: #92400e;"><i class="fas fa-clock"></i></div>
                        <div class="card-info"><h3><?= $pending_booking ?></h3><p>Menunggu Konfirmasi</p></div>
                    </div>
                    <div class="card">
                        <div class="card-icon" style="background: #dcfce7; color: #166534;"><i class="fas fa-check-circle"></i></div>
                        <div class="card-info"><h3><?= $success_booking ?></h3><p>Booking Berhasil</p></div>
                    </div>
                    <div class="card">
                        <div class="card-icon" style="background: #fce7f3; color: #9d174d;"><i class="fas fa-user-friends"></i></div>
                        <div class="card-info"><h3><?= $total_users ?></h3><p>Total Pendaki</p></div>
                    </div>
                </div>
            </div>

            <div class="tab-content <?= $active_menu == 'bookings' ? 'active' : '' ?>">
                <div class="table-container">
                    <div class="table-header"><h2>Daftar Booking Masuk</h2></div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama/Email</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $bookings_res->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td><?= $row['full_name'] ?><br><small><?= $row['email'] ?></small></td>
                                <td><?= date('d M Y', strtotime($row['departure_date'])) ?></td>
                                <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                                <td>
                                    <form method="POST" style="display: inline-flex; gap: 5px;">
                                        <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        <button name="status" value="confirmed" class="btn-approve" title="Setujui"><i class="fas fa-check"></i></button>
                                        <button name="status" value="cancelled" class="btn-cancel" title="Batalkan"><i class="fas fa-times"></i></button>
                                    </form>
                                    <a href="?menu=bookings&hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" style="color: grey; margin-left: 10px;"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content <?= $active_menu == 'users' ? 'active' : '' ?>">
                <div class="table-container">
                    <div class="table-header"><h2>Daftar Pengguna Terdaftar</h2></div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users_res->fetch_assoc()): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><span style="background:#eee; padding:3px 8px; border-radius:4px; font-size:12px;"><?= strtoupper($user['role']) ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>
</html>