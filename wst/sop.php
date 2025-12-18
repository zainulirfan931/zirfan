<?php
session_start(); 
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOP Pendakian - Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* CSS Khusus Halaman SOP agar lebih menarik */
    .sop-header {
      background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
      color: white;
      padding: 60px 0;
      text-align: center;
      margin-bottom: 40px;
    }

    .sop-header h2 { font-size: 2.5rem; margin-bottom: 10px; }
    .sop-header p { opacity: 0.9; font-size: 1.1rem; }

    .sop-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
      margin-bottom: 50px;
    }

    .sop-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      border-top: 5px solid #d97706; /* Warna aksen oranye */
      transition: transform 0.3s ease;
    }

    .sop-card:hover { transform: translateY(-5px); }

    .sop-card h3 {
      color: #1e3a8a;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      font-size: 1.3rem;
    }

    .sop-card ul { list-style: none; padding: 0; }
    .sop-card ul li {
      position: relative;
      padding-left: 25px;
      margin-bottom: 12px;
      color: #475569;
      line-height: 1.6;
    }

    /* Ikon Checklist Custom */
    .sop-card ul li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #15803d;
      font-weight: bold;
    }

    /* Variasi warna border untuk setiap kategori */
    .card-danger { border-top-color: #dc2626; }
    .card-warning { border-top-color: #eab308; }
    .card-success { border-top-color: #16a34a; }

    .important-note {
      background: #fffbeb;
      border-left: 5px solid #d97706;
      padding: 20px;
      border-radius: 8px;
      margin-top: 30px;
      font-style: italic;
      color: #92400e;
    }
  </style>
</head>
<body>

  <header class="navbar">
    <div class="container">
      <div class="logo">
        <div class="logo-box">GP</div>
        <div>
          <h1>Gunung Pesagi</h1>
          <p>Pendakian Aman & Lestari</p>
        </div>
      </div>

      <nav>
        <a href="index.php">Home</a>
        <a href="objek-wisata.php">Objek Wisata</a>
        <a href="booking.php">Booking</a>
        <a href="sop.php" class="active">S O P</a>
        <a href="cek-kuota.php">Cek Kuota</a>
        
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php" title="Lihat Profil">
                <span style="font-weight: 600; color: #1e3a8a;">
                    👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
            </a>
            <a href="logout.php" style="color: #dc2626; font-weight: 600;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <div class="sop-header">
    <div class="container">
      <h2>Standar Operasional Prosedur</h2>
      <p>Panduan resmi demi keselamatan pendaki dan kelestarian alam Gunung Pesagi</p>
    </div>
  </div>

  <section class="container">
    <div class="sop-grid">
      
      <div class="sop-card">
        <h3>📋 Ketentuan Umum</h3>
        <ul>
          <li>Wajib mendaftar resmi melalui sistem booking online.</li>
          <li>Membawa kartu identitas asli (KTP/SIM/Paspor).</li>
          <li>Kapasitas tim harus sesuai dengan kuota yang tersedia.</li>
          <li>Wajib mengikuti briefing singkat di basecamp resmi.</li>
        </ul>
      </div>

      <div class="sop-card card-success">
        <h3>🎒 Persiapan Wajib</h3>
        <ul>
          <li>Membawa perlengkapan gunung lengkap & layak pakai.</li>
          <li>Kondisi fisik dalam keadaan sehat (disarankan bawa surat sehat).</li>
          <li>Membawa suplai air minimal 3 liter per orang.</li>
          <li>Mempersiapkan logistik makanan yang ramah lingkungan.</li>
        </ul>
      </div>

      <div class="sop-card card-danger">
        <h3>🚫 Larangan Keras</h3>
        <ul>
          <li>Dilarang membawa alkohol, miras, dan obat terlarang.</li>
          <li>Dilarang membuat api unggun (gunakan kompor portable).</li>
          <li>Dilarang membuang sampah (Wajib dibawa turun kembali).</li>
          <li>Dilarang memetik flora atau memburu fauna di area konservasi.</li>
        </ul>
      </div>

      <div class="sop-card card-warning">
        <h3>🚨 Prosedur Darurat</h3>
        <ul>
          <li>Segera lapor ke petugas jika terjadi kecelakaan/insiden.</li>
          <li>Gunakan jalur evakuasi resmi yang telah ditentukan.</li>
          <li>Ikuti instruksi tim SAR atau pengelola jika cuaca buruk.</li>
          <li>Tetap bersama kelompok, dilarang memisahkan diri.</li>
        </ul>
      </div>

    </div>

    <div class="important-note">
      <strong>Catatan Penting:</strong> Pelanggaran terhadap poin-poin di atas dapat dikenakan sanksi berupa denda administratif hingga larangan pendakian seumur hidup (Blacklist).
    </div>
  </section>

  <section class="footer-main" style="margin-top: 60px;">
    <div class="container footer-grid">
      <div class="footer-logo">
        <h2><span style="color:#1d4ed8;">Booking Online</span><br><span style="color:#15803d;">Gunung Pesagi</span></h2>
      </div>
      <div>
        <h4>INFORMASI</h4>
        <p>📍 Remanam Jaya, Kec. Warkuk Ranau Sel., OKU Selatan, Sumatera Selatan</p>
        <p>📞 Helpdesk: +62 82279036699</p>
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