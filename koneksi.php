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

// Auto create settings table if not exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
)");

// Insert default value if not exists
$check_settings = mysqli_query($conn, "SELECT * FROM settings WHERE setting_key = 'active_event'");
if (mysqli_num_rows($check_settings) == 0) {
    mysqli_query($conn, "INSERT INTO settings (setting_key, setting_value) VALUES ('active_event', 'none')");
}
?>
