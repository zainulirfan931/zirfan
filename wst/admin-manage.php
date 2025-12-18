<?php
session_start();
// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$msg = '';
$active_menu = isset($_GET['menu']) ? $_GET['menu'] : 'destinations';

// --- LOGIKA: KELOLA OBJEK WISATA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_destination'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    
    $stmt = $conn->prepare("INSERT INTO destination (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $desc);
    if ($stmt->execute()) { $msg = "Objek wisata berhasil ditambahkan!"; }
    $stmt->close();
}

if (isset($_GET['del_dest'])) {
    $id = intval($_GET['del_dest']);
    $conn->query("DELETE FROM destination WHERE id = $id");
    header("Location: admin-manage.php?menu=destinations");
    exit();
}

// --- LOGIKA: KELOLA KUOTA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set_quota'])) {
    $date = $_POST['quota_date'];
    $max = $_POST['max_quota'];
    
    $stmt = $conn->prepare("INSERT INTO quota_settings (quota_date, max_quota) VALUES (?, ?) ON DUPLICATE KEY UPDATE max_quota = ?");
    $stmt->bind_param("sii", $date, $max, $max);
    if ($stmt->execute()) { $msg = "Kuota tanggal $date berhasil diperbarui!"; }
    $stmt->close();
}

// Ambil Data
$destinations = $conn->query("SELECT * FROM destination ORDER BY id DESC");
$quotas = $conn->query("SELECT * FROM quota_settings ORDER BY quota_date ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Content - Admin Panel</title>
    <link rel="stylesheet" href="admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><div class="logo">GP</div> <span>Admin</span></h2>
        </div>
        <nav class="sidebar-nav">
            <a href="admin-booking.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            <a href="admin-booking.php"><i class="fas fa-calendar-check"></i> <span>Kelola Booking</span></a>
    
            <a href="admin-manage.php?menu=destinations" class="<?= $active_menu == 'destinations' ? 'active' : '' ?>">
                <i class="fas fa-map-marked-alt"></i> <span>Kelola Wisata</span>
            </a>    
            <a href="admin-manage.php?menu=quota" class="<?= $active_menu == 'quota' ? 'active' : '' ?>">
                <i class="fas fa-mountain"></i> <span>Atur Kuota</span>
            </a>
    
            <a href="admin-booking.php?menu=users"><i class="fas fa-users"></i> <span>Pengguna</span></a>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </nav>
    </div>

    <div class="main-content">
        <header class="main-header">
            <h1>Pengaturan Konten & Kuota</h1>
            <?php if($msg): ?>
                <div style="background:#dcfce7; color:#166534; padding:10px; border-radius:5px; margin-top:10px;"><?= $msg ?></div>
            <?php endif; ?>
        </header>

        <div class="content-body">
            <?php if($active_menu == 'destinations'): ?>
                <div class="table-container">
                    <div class="table-header"><h2>Tambah Objek Wisata Baru</h2></div>
                    <form method="POST" style="padding:20px; border-bottom:1px solid #eee;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
                            <input type="text" name="name" class="form-control" placeholder="Nama Lokasi" required>
                            <input type="number" name="price" class="form-control" placeholder="Harga Tiket" required>
                        </div>
                        <textarea name="description" class="form-control" placeholder="Deskripsi Singkat" rows="3"></textarea>
                        <button type="submit" name="add_destination" class="btn-login" style="margin-top:15px; width:200px;">Tambah Lokasi</button>
                    </form>

                    <table>
                        <thead>
                            <tr><th>Nama Lokasi</th><th>Harga</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php while($d = $destinations->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
                                <td>Rp <?= number_format($d['price'], 0, ',', '.') ?></td>
                                <td><a href="?del_dest=<?= $d['id'] ?>" onclick="return confirm('Hapus lokasi ini?')" style="color:red;"><i class="fas fa-trash"></i></a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif($active_menu == 'quota'): ?>
                <div class="table-container">
                    <div class="table-header"><h2>Atur Batas Kuota Harian</h2></div>
                    <form method="POST" style="padding:20px; display:flex; gap:15px; align-items:center;">
                        <input type="date" name="quota_date" class="form-control" required>
                        <input type="number" name="max_quota" class="form-control" placeholder="Jumlah Kuota" required>
                        <button type="submit" name="set_quota" class="btn-login" style="width:200px;">Simpan Kuota</button>
                    </form>

                    <table>
                        <thead>
                            <tr><th>Tanggal</th><th>Maks. Kuota</th><th>Terisi</th><th>Sisa</th></tr>
                        </thead>
                        <tbody>
                            <?php while($q = $quotas->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d F Y', strtotime($q['quota_date'])) ?></td>
                                <td><?= $q['max_quota'] ?> Orang</td>
                                <td><?= $q['current_used'] ?> Orang</td>
                                <td style="font-weight:bold; color:green;"><?= $q['max_quota'] - $q['current_used'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>