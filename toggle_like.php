<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['song_id'])) {
    $song_id = intval($_POST['song_id']);
    $user_id = intval($_SESSION['user_id']);
    
    // Check if like exists
    $check_query = "SELECT id FROM liked_songs WHERE user_id = $user_id AND song_id = $song_id";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        // Unlike
        mysqli_query($conn, "DELETE FROM liked_songs WHERE user_id = $user_id AND song_id = $song_id");
        echo json_encode(['status' => 'success', 'action' => 'unliked']);
    } else {
        // Like
        mysqli_query($conn, "INSERT INTO liked_songs (user_id, song_id) VALUES ($user_id, $song_id)");
        echo json_encode(['status' => 'success', 'action' => 'liked']);
    }
}
?>
