<?php
// koneksi.php - Database Configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "music_stream";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
