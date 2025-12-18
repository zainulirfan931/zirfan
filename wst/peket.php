<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Paket Pendakian Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- HEADER ► sama seperti halaman utama -->
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
        <a href="paket.php" class="active">Paket</a>
        <a href="#booking">Booking</a>
        <a href="#SOP">S O P</a>
        <button class="btn-primary">Cek Kouta</button>
        <a href="login.php">Login</a>
      </nav>
    </div>
  </header>

  <!-- SECTION: PAKET -->
  <section class="container" style="padding:50px 0;">
    <h2 style="text-align:center;">Paket Pendakian Gunung Pesagi</h2>
    <p style="text-align:center; max-width:600px; margin:auto;">
      Pilih paket pendakian yang sesuai dengan kebutuhan dan budget Anda. Semua paket sudah termasuk guide resmi, perlengkapan dasar, dan keamanan standar.
    </p>

    <div class="packages" style="margin-top:40px;">
      <div class="card">
        <h4>Reguler</h4>
        <p class="price">Rp 150.000</p>
        <ul>
          <li>Guide lokal</li>
          <li>Peralatan dasar</li>
        </ul>
        <a href="index.php#booking" class="btn-outline">Pilih Paket</a>
      </div>

      <div class="card highlight">
        <h4>Premium</h4>
        <p class="price">Rp 300.000</p>
        <ul>
          <li>Guide profesional</li>
          <li>Tenda premium</li>
          <li>Makan siang + malam</li>
        </ul>
        <a href="index.php#booking" class="btn-primary">Pilih Paket</a>
      </div>

      <div class="card">
        <h4>VIP</h4>
        <p class="price">Rp 550.000</p>
        <ul>
          <li>Guide pribadi</li>
          <li>Dokumentasi Foto & Video</li>
          <li>Transport PP</li>
        </ul>
        <a href="index.php#booking" class="btn-outline">Pilih Paket</a>
      </div>
    </div>
  </section>

</body>
</html>
