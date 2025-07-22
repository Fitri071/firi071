<?php
// Mulai sesi lebih awal di semua halaman
session_start();

$host = 'localhost';
$db   = 'datamahasiswa';
$user = 'root';
$pass = '';           // kosongkan jika root tanpa password
$opt  = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $opt);
} catch (PDOException $e) {
    exit("Koneksi database gagal: " . $e->getMessage());
}
