<?php
require 'koneksi.php';

$queries = [
    "ALTER TABLE songs ADD COLUMN lyrics TEXT",
    "CREATE TABLE IF NOT EXISTS playlists (id INT PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, name VARCHAR(100) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)",
    "CREATE TABLE IF NOT EXISTS playlist_songs (id INT PRIMARY KEY AUTO_INCREMENT, playlist_id INT NOT NULL, song_id INT NOT NULL, added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE, FOREIGN KEY (song_id) REFERENCES songs(id) ON DELETE CASCADE)"
];

foreach ($queries as $q) {
    if(mysqli_query($conn, $q)) {
        echo "Success: " . substr($q, 0, 30) . "...\n";
    } else {
        echo "Error or already exists: " . mysqli_error($conn) . "\n";
    }
}
?>
