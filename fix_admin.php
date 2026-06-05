<?php
require 'koneksi.php';
// Correct hash for 'admin123'
$correct_hash = password_hash('admin123', PASSWORD_DEFAULT);

$query = "UPDATE users SET password='$correct_hash'";
if(mysqli_query($conn, $query)){
    echo "<h1>Semua password berhasil direset!</h1>";
    echo "<p>Silakan kembali ke <a href='login.php'>Halaman Login</a> dan masuk menggunakan password: <b>admin123</b>.</p>";
    echo "<p><i>(Anda bisa menghapus file fix_admin.php ini setelah berhasil login)</i></p>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
