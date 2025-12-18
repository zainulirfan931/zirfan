<?php
session_start();
require_once 'db.php';

// Pastikan user sudah login sebelum booking
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Online - Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary: #d97706;
      --secondary: #15803d;
      --accent: #d97706;
    }

    .booking-container {
      display: grid;
      grid-template-columns: 1fr 350px;
      gap: 30px;
      margin-top: 40px;
      margin-bottom: 60px;
    }

    /* Tampilan Paket */
    .package-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .package-card {
      background: white;
      border: 2px solid #eee;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .package-card:hover {
      border-color: var(--primary);
      transform: translateY(-5px);
    }

    .package-card.selected {
      border-color: var(--primary);
      background-color: #eff6ff;
      box-shadow: 0 4px 15px rgba(30, 58, 138, 0.1);
    }

    .package-card h3 { color: var(--primary); margin-bottom: 10px; }
    .package-card .price { font-size: 1.2rem; font-weight: bold; color: var(--secondary); }

    /* Form Styling */
    .booking-form-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; }
    .form-control {
      width: 100%;
      padding: 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 1rem;
    }

    /* Summary Card */
    .summary-card {
      background: #f1f5f9;
      padding: 25px;
      border-radius: 15px;
      position: sticky;
      top: 100px;
      height: fit-content;
    }

    .summary-card h4 { border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; margin-bottom: 20px; }
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 10px; }
    .total-price { font-size: 1.4rem; font-weight: bold; color: var(--primary); margin-top: 20px; border-top: 1px solid #cbd5e1; padding-top: 15px; }

    .btn-submit {
      width: 100%;
      background: var(--primary);
      color: white;
      padding: 15px;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
    }

    @media (max-width: 768px) {
      .booking-container { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <header class="navbar">
    <div class="container">
      <div class="logo">
        <div class="logo-box">GP</div>
        <div><h1>Booking Online Pesagi</h1><p>Petualangan asik dan aman</p></div>
      </div>
      <nav>
        <a href="index.php">Home</a>
        <a href="objek-wisata.php">Objek Wisata</a>
        <a href="booking.php" class="active">Booking</a>
        <a href="sop.php">S O P</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">👤 <?= htmlspecialchars($_SESSION['username']) ?></a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="container">
    <section style="margin-top: 40px;">
      <h2 style="text-align:center; color: var(--primary);">Pilih Paket Pendakian</h2>
      <p style="text-align:center; margin-bottom: 30px;">Silakan pilih paket yang sesuai dengan kebutuhan Anda</p>
      
      <div class="package-grid">
        <div class="package-card" onclick="selectPackage('Ekonomi', 150000)">
          <h3>Ekonomi</h3>
          <p>Tiket Masuk + Asuransi</p>
          <div class="price">Rp 150.000</div>
        </div>
        <div class="package-card" onclick="selectPackage('Standar', 250000)">
          <h3>Standar</h3>
          <p>Tiket + Porter (Grup)</p>
          <div class="price">Rp 250.000</div>
        </div>
        <div class="package-card" onclick="selectPackage('Premium', 500000)">
          <h3>Premium</h3>
          <p>Fasilitas Lengkap + Makan</p>
          <div class="price">Rp 500.000</div>
        </div>
      </div>
    </section>

    <div class="booking-container">
      <div class="booking-form-card">
        <form action="proses-booking.php" method="POST" id="bookingForm">
          <div class="form-group">
            <label>Paket Dipilih</label>
            <input type="text" name="package_name" id="inputPackage" class="form-control" readonly placeholder="Pilih paket di atas..." required>
          </div>
          <div class="form-group">
            <label>Tanggal Keberangkatan</label>
            <input type="date" name="departure_date" class="form-control" required min="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label>Jumlah Pendaki</label>
            <input type="number" name="total_person" id="inputPerson" class="form-control" value="1" min="1" required oninput="updateSummary()">
          </div>
          <div class="form-group">
            <label>Catatan Tambahan</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
          <button type="submit" class="btn-submit">Konfirmasi Booking Sekarang</button>
        </form>
      </div>

      <div class="summary-card">
        <h4>Ringkasan Pesanan</h4>
        <div class="summary-item">
          <span>Paket:</span>
          <span id="sumPackage">-</span>
        </div>
        <div class="summary-item">
          <span>Harga:</span>
          <span id="sumPrice">Rp 0</span>
        </div>
        <div class="summary-item">
          <span>Jumlah:</span>
          <span id="sumPerson">1 Orang</span>
        </div>
        <div class="total-price">
          <span>Total:</span>
          <span id="sumTotal">Rp 0</span>
        </div>
      </div>
    </div>
  </main>

  <script>
    let selectedPrice = 0;

    function selectPackage(name, price) {
      // Set value ke input form
      document.getElementById('inputPackage').value = name;
      selectedPrice = price;

      // Update Visual Kartu
      document.querySelectorAll('.package-card').forEach(card => {
        card.classList.remove('selected');
        if(card.querySelector('h3').innerText === name) {
          card.classList.add('selected');
        }
      });

      updateSummary();
    }

    function updateSummary() {
      const name = document.getElementById('inputPackage').value;
      const person = document.getElementById('inputPerson').value;
      const total = selectedPrice * person;

      document.getElementById('sumPackage').innerText = name || '-';
      document.getElementById('sumPrice').innerText = 'Rp ' + selectedPrice.toLocaleString();
      document.getElementById('sumPerson').innerText = person + ' Orang';
      document.getElementById('sumTotal').innerText = 'Rp ' + total.toLocaleString();
    }
  </script>

</body>
</html>