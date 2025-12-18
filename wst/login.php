<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect ke halaman sesuai role
                if ($user['role'] == 'admin') {
                    header("Location: admin-booking.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username/email tidak ditemukan!";
        }
        $stmt->close();
    } else {
        $error = "Username dan password harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Gunung Pesagi Booking</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="login-register-style.css" />
</head>
<body>

  <!-- HEADER - SAMA PERSIS -->
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

  <!-- LOGIN SECTION -->
  <section class="login-container">
    <div class="login-box"> <div class="login-header">
        <h2>Masuk ke Akun</h2>
        <p>Silakan login untuk mengakses layanan booking</p>
      </div>
      
      <?php if ($error): ?>
          <div class="alert alert-error">
              <?php echo htmlspecialchars($error); ?>
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
            placeholder="Masukkan username"
            required
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
          >
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            placeholder="Masukkan password"
            required
          >
        </div>
        
        <button type="submit" class="btn-login">Masuk</button>
        
        <div class="login-footer">
          Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
      </form>
      
    </div> </section>

  <!-- FOOTER - SAMA PERSIS -->
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

  <script>
    // Auto focus ke username field
    document.getElementById('username').focus();
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();
      
      if (!username) {
        e.preventDefault();
        alert('Username harus diisi');
        document.getElementById('username').focus();
        return false;
      }
      
      if (!password) {
        e.preventDefault();
        alert('Password harus diisi');
        document.getElementById('password').focus();
        return false;
      }
      
      return true;
    });
  </script>

</body>
</html>