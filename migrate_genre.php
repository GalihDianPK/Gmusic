<?php
require 'koneksi.php';

$query = "ALTER TABLE songs ADD COLUMN genre VARCHAR(50) DEFAULT 'Pop'";

if(mysqli_query($conn, $query)) {
    echo "Genre column added successfully.\n";
} else {
    echo "Error or column already exists: " . mysqli_error($conn) . "\n";
}
?>
