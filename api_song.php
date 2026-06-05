<?php
session_start();
require 'koneksi.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID']);
    exit();
}

$song_id = intval($_GET['id']);
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

$song_q = mysqli_query($conn, "SELECT file_video FROM songs WHERE id = $song_id");
$video = null;
if ($row = mysqli_fetch_assoc($song_q)) {
    $video = $row['file_video'];
}

$is_liked = false;
if ($user_id > 0) {
    $like_q = mysqli_query($conn, "SELECT id FROM liked_songs WHERE user_id = $user_id AND song_id = $song_id");
    if(mysqli_num_rows($like_q) > 0) $is_liked = true;
}

echo json_encode([
    'file_video' => $video,
    'is_liked' => $is_liked
]);
?>
