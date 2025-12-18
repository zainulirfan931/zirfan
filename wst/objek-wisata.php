<?php
session_start(); // HARUS ADA DI BARIS PALING ATAS
require_once 'db.php'; 
// ... kode PHP lainnya jika ada ...
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Objek Wisata – Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HEADER -->
<header class="navbar">
  <div class="container">
    <div class="logo">
      <div class="logo-box">GP</div>
      <div>
        <h1>Booking Online Pesagi</h1>
        <p>Petualangan asik dan aman</p>
      </div>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="objek-wisata.php">Objek Wisata</a>
        <a href="booking.php">Booking</a>
        <a href="sop.php">S O P</a>
        <a href="cek-kuota.php">Cek Kuota</a>
        
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php" title="Lihat Profil">
                <span style="font-weight: 600; color: #1e3a8a;">
                    👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
            </a>
            <a href="logout.php" style="color: #dc2626; font-weight: 600;">Logout</a>
        <?php else: ?>
            <a href="login.php" class="active">Login</a>
        <?php endif; ?>
      </nav>
  </div>
</header>

<!-- PAGE TITLE -->
<section class="page-title">
  <div class="container">
    <h2>Objek Wisata Pesagi</h2>
    <p>Jelajahi keindahan alam sekitar Gunung Pesagi</p>
  </div>
</section>

<!-- OBJEK WISATA LIST -->
<section class="container objek-wisata">
  <div class="tour-grid">

    <div class="tour-card large">
      <img src="lembah.jpg"> 
      <div class="tour-info">
        <h3>Top Selfie</h3>
        <p>Spot foto terbaik dengan panorama alam yang indah dan instagramable.</p>
        <a href="top-selfie.php" class="btn-primary small">Lihat Detail</a>

      </div>
    </div>

    <div class="tour-card large">
      <img src="batu.jpg"> 
      <div class="tour-info">
        <h3>Pesona Batu Barak</h3>
        <p>Batuan alami yang memancarkan warna unik ketika terkena cahaya.</p>
        <a href="top-selfie.php" class="btn-primary small">Lihat Detail</a>

      </div>
    </div>

    <div class="tour-card large">
      <img src="air terjun.jpg"> 
      <div class="tour-info">
        <h3>Air Terjun Pancormas</h3>
        <p>Air terjun cantik dengan suasana hutan yang asri dan sejuk.</p>
        <a href="top-selfie.php" class="btn-primary small">Lihat Detail</a>

      </div>
    </div>

  </div>
</section>

<!-- FOOTER -->
  <section class="footer-main">
    <div class="container footer-grid">
      <div class="footer-logo">
        <h2><span style="color:#1d4ed8;">Booking Online</span><br><span style="color:#15803d;">Objek Wisata Pesawaran</span></h2>
      </div>

      <div>
        <h4>INFORMASI</h4>
        <p>📍 Remanam Jaya, Kec. Warkuk Ranau Sel., Kabupaten Ogan Komering Ulu Selatan, Sumatera Selatan</p>
        <p>📞 Helpdesk: +62 82279036699</p>
        <p>✉️ info@gunungpesagi.id</p>
      </div>

      <div>
        <h4>LINK TERKAIT</h4>
        <p><a href="#packages">Destinasi Wisata</a></p>
        <p><a href="#panduan">Panduan Booking</a></p>
        <p><a href="#">Kebijakan Privasi</a></p>
      </div>

      <div>
        <h4>IKUTI KAMI</h4>
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="25"></a>
      </div>
    </div>
  </section>

  <footer class="footer-bottom">
    © 2025 Booking Online Gunung Pesagi — All rights reserved.
  </footer>

  <a href="https://wa.me/6282279036699" class="whatsapp-float" target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp">
  </a>


</body>
</html>
