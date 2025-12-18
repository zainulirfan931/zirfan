<?php
session_start(); // HARUS ADA DI BARIS PALING ATAS
require_once 'db.php'; 
// ... kode PHP lainnya jika ada ...
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gunung Pesagi Booking</title>
  <link rel="stylesheet" href="style.css" />
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

  <!-- HERO SECTION -->
  <section id="hero" class="hero container">
    <div class="hero-text">
      <h2>Rasakan Petualangan Eksklusif ke <span>Gunung Pesagi</span></h2>
      <p>Pilih paket yang rapi, aman, dan nyaman — pengalaman lengkap dengan guide lokal yang ramah, makanan, dan dokumentasi profesional.</p>
      <div class="hero-buttons">
        <a href="booking.php" class="btn-primary">Pesan Sekarang</a>
      </div>
      <div class="features">
        <div><strong>Keamanan</strong><p>Peralatan & guide tersertifikasi</p></div>
        <div><strong>Transport</strong><p>Opsional antar-jemput</p></div>
        <div><strong>Dokumentasi</strong><p>Foto & video profesional (VIP)</p></div>
      </div>
    </div>

    <div class="hero-image">
      <img src="gunung.jpg">
      <div class="info-box"></div>
    </div>
  </section>

  
  <!-- MAPS SECTION -->
  <section id="maps" style="text-align:center; padding:40px 0;">
    <h2>Lokasi Gunung Pesagi</h2>
    <p>Temukan lokasi Gunung Pesagi secara langsung di peta berikut:</p>
    <div style="margin-top:20px;">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63722.35893817792!2d104.0031063!3d-5.052353399999983!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e4fc95ccfd8a0d1%3A0x5cc6a3bde9112b36!2sGunung%20Pesagi!5e0!3m2!1sid!2sid!4v1730669834567!5m2!1sid!2sid" 
        width="100%" 
        height="450" 
        style="border:0; border-radius:10px;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
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
