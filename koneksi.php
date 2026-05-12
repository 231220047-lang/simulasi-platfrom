<?php
$host = "sql303.infinityfree.com"; 
$user = "if0_41901394"; 
$pass = "yjSnqSOptAY"; // Klik ikon mata di screenshot kamu untuk melihatnya
$db   = "if0_41901394_epiz_platfrom_toko_db"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>