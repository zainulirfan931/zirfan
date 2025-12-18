<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari SESSION karena tidak ada di form booking.php
    $full_name = $_SESSION['username'] ?? 'Guest';
    $email = $_SESSION['email'] ?? 'No Email';
    
    // Mengambil data dari FORM (sesuaikan dengan 'name' di booking.php)
    $phone = $_POST['phone'] ?? '-'; // Tambahkan input phone di form jika perlu
    $departure_date = $_POST['departure_date'];
    $package = $_POST['package_name']; // Mengambil dari name="package_name"
    $quantity = $_POST['total_person']; // Mengambil dari name="total_person"
    $notes = $_POST['notes'];
    
    // Generate booking code
    $booking_code = 'PSG' . date('Ymd') . rand(1000, 9999);
    
    // Harga paket (sesuaikan dengan harga di booking.php)
    $prices = [
        'Ekonomi' => 150000,
        'Standar' => 250000,
        'Premium' => 500000
    ];
    $price_per_person = $prices[$package] ?? 0;
    $total_amount = $price_per_person * $quantity;
    
    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO bookings 
        (booking_code, full_name, email, phone, departure_date, package, quantity, total_amount, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssids", 
        $booking_code, $full_name, $email, $phone, $departure_date, 
        $package, $quantity, $total_amount, $notes
    );
    
    if ($stmt->execute()) {
        echo "<script>alert('Booking berhasil! Kode: $booking_code'); window.location.href = 'profile.php?menu=riwayat';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>