<?php
session_start();
require_once 'db.php'; // Memastikan koneksi ke database 'wst'

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 2. Bersihkan dan validasi data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Bersihkan input
        $username_clean = mysqli_real_escape_string($conn, $username);
        $email_clean = mysqli_real_escape_string($conn, $email);
        
        // 3. Cek apakah username atau email sudah terdaftar
        $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username_clean, $email_clean);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            // 4. Hash password dan simpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $default_role = 'user'; // Atur peran default
            
            $sql_insert = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $username_clean, $email_clean, $hashed_password, $default_role);
            
            if ($stmt_insert->execute()) {
                $success = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
                
                // Opsional: Kosongkan variabel POST setelah sukses
                unset($_POST); 
            } else {
                $error = "Registrasi gagal. Coba lagi nanti.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrasi - Gunung Pesagi Booking</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="login-register-style.css" />
</head>
<body>

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

  <section class="login-container">
    <div class="login-box">
      <div class="login-header">
        <h2>Daftar Akun Baru</h2>
        <p>Silakan isi data diri untuk membuat akun booking</p>
      </div>
      
      <?php if ($error): ?>
          <div class="alert alert-error">
              <?php echo htmlspecialchars($error); ?>
          </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
          <div class="alert alert-success">
              <?php echo $success; ?>
          </div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="form-group">
          <label for="username">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            class="form-control" 
            placeholder="Buat username"
            required
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
          >
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            placeholder="Masukkan email aktif"
            required
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
          >
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            placeholder="Buat password (min 6 karakter)"
            required
          >
        </div>
        
        <div class="form-group">
          <label for="confirm_password">Konfirmasi Password</label>
          <input 
            type="password" 
            id="confirm_password" 
            name="confirm_password" 
            class="form-control" 
            placeholder="Ulangi password"
            required
          >
        </div>
        
        <button type="submit" class="btn-login">Daftar Sekarang</button>
      </form>
      
      <div class="login-footer">
        Sudah punya akun? <a href="login.php">Masuk di sini</a>
      </div>
    </div>
  </section>

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
        <p><a href="objek-wisata.php">Destinasi Wisata</a></p>
        <p><a href="sop.php">Panduan Booking</a></p>
        <p><a href="#">Kebijakan Privasi</a></p>
      </div>

      <div>
        <h4>IKUTI KAMI</h4>
        <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" width="25" alt="Instagram"></a>
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