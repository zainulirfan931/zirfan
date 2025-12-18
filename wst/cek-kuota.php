<?php
session_start();
require_once 'db.php';

// Ambil data kuota mulai dari hari ini ke depan
// CURDATE() memastikan sistem hanya mengambil jadwal hari ini dan masa depan
$query = "SELECT * FROM daily_quotas WHERE quota_date >= CURDATE() ORDER BY quota_date ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cek Kuota Pendakian - Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary: #15803d;
      --warning: #d97706;
      --danger: #dc2626;
    }

    .quota-section { padding: 40px 0; background: #f9fafb; min-height: 80vh; }
    .header-info { text-align: center; margin-bottom: 40px; }
    .header-info h2 { color: #1e3a8a; font-size: 2rem; margin-bottom: 10px; }

    /* Desain Tabel Responsif */
    .table-container {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
      overflow-x: auto; /* Penting untuk HP */
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }

    th {
      background: #f3f4f6;
      color: #374151;
      font-weight: 600;
      padding: 15px;
      text-align: left;
      border-bottom: 2px solid #e5e7eb;
    }

    td {
      padding: 15px;
      border-bottom: 1px solid #f3f4f6;
      color: #4b5563;
    }

    .badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: bold;
    }

    .badge-available { background: #dcfce7; color: #15803d; }
    .badge-limited { background: #fef3c7; color: #d97706; }
    .badge-full { background: #fee2e2; color: #dc2626; }

    .btn-booking-small {
      background: var(--primary);
      color: white;
      padding: 6px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .header-info h2 { font-size: 1.5rem; }
      td, th { padding: 10px; font-size: 0.9rem; }
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
    <div class="logo">
       <h1 style="font-size:1.2rem; margin:0;">Booking Pesagi</h1>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="booking.php" style="background:var(--warning); color:white; padding:5px 15px; border-radius:5px;">Booking Sekarang</a>
    </nav>
  </div>
</header>

<section class="quota-section">
  <div class="container">
    <div class="header-info">
      <h2>Jadwal & Kuota Pendakian</h2>
      <p>Silakan pilih tanggal keberangkatan yang masih tersedia.</p>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Tanggal Keberangkatan</th>
            <th>Maks. Kuota</th>
            <th>Terisi</th>
            <th>Sisa Kuota</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): 
              $sisa = $row['max_quota'] - $row['current_used'];
              $status_class = 'badge-available';
              $status_text = 'Tersedia';

              if($sisa <= 0) {
                $status_class = 'badge-full';
                $status_text = 'Penuh';
              } elseif($sisa < 10) {
                $status_class = 'badge-limited';
                $status_text = 'Terbatas';
              }
            ?>
            <tr>
              <td><strong><?= date('d M Y', strtotime($row['quota_date'])) ?></strong></td>
              <td><?= $row['max_quota'] ?></td>
              <td><?= $row['current_used'] ?></td>
              <td style="font-weight:bold; color: <?= $sisa <= 0 ? 'red' : 'green' ?>;"><?= $sisa ?></td>
              <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
              <td>
                <?php if($sisa > 0): ?>
                  <a href="booking.php?date=<?= $row['quota_date'] ?>" class="btn-booking-small">Pesan</a>
                <?php else: ?>
                  <span style="color:#999; font-size:0.8rem;">Ditutup</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align:center; padding:40px;">
                Belum ada data jadwal untuk bulan ini. <br>
                <small>Admin perlu mengatur kuota di menu "Kelola Kuota".</small>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

</body>
</html>