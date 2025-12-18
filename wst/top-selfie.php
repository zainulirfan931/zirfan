<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Top Selfie – Gunung Pesagi</title>
  <link rel="stylesheet" href="style.css">
  <style>

    
    .detail-container {
      display: flex;
      gap: 30px;
      padding: 40px 20px;
      max-width: 1200px;
      margin: auto;
    }

    .detail-content {
      flex: 3;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .detail-sidebar {
      flex: 1;
    }

    .sidebar-item {
      background: white;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .sidebar-item img {
      width: 100%;
      border-radius: 8px;
    }

    .detail-content img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }

    .detail-meta {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #555;
      margin-bottom: 10px;
    }
  </style>
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
        <a href="login.php">Login</a>

      </nav>
    </div>
  </header>

<section class="detail-container">

  <!-- KONTEN UTAMA -->
  <div class="detail-content">
    <h2>Top Selfie Gunung Pesagi</h2>

    <div class="detail-meta">
      <span>By Admin</span> · 
      <span>Januari 10, 2025</span>
    </div>

    <img src="lembah.jpg"> 

    <p>
      Top Selfie merupakan salah satu spot foto terbaik di kawasan Gunung Pesagi
      dengan panorama hutan, lembah, dan perbukitan yang sangat indah. Tempat ini
      menjadi lokasi favorit para pendaki untuk beristirahat sekaligus mengabadikan momen.
    </p>

    <p>
      Dari lokasi ini, pengunjung dapat melihat hamparan alam yang luas dengan suasana yang asri
      dan udara yang sangat sejuk. Jalan menuju spot ini cukup mudah dilalui dan cocok untuk
      pendaki pemula.
    </p>

  </div>

  <!-- SIDEBAR -->
  <div class="detail-sidebar">

    <div class="sidebar-item">
      <img src="batu.jpg"> 
      <p><b>Pesona Batu Barak</b></p>
    </div>

    <div class="sidebar-item">
      <img src="air terjun.jpg"> 
      <p><b>Air Terjun Pancormas</b></p>
    </div>

  </div>

</section>

</body>
</html>
