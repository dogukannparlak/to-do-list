<?php
// db_config.php

// Veritabanı bağlantı bilgileri
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "mydatabase";

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>
