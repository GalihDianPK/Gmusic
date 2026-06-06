<?php
session_start();
require '../koneksi.php';

// Check if user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch info before deleting to remove files
    $query = "SELECT * FROM songs WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $song = mysqli_fetch_assoc($result);
    
    if($song) {
        // Delete files from uploads directory
        if(!empty($song['cover_image']) && file_exists("../uploads/covers/" . $song['cover_image'])) {
            unlink("../uploads/covers/" . $song['cover_image']);
        }
        if(!empty($song['file_audio']) && file_exists("../uploads/audio/" . $song['file_audio'])) {
            unlink("../uploads/audio/" . $song['file_audio']);
        }
        if(!empty($song['file_video']) && file_exists("../uploads/video/" . $song['file_video'])) {
            unlink("../uploads/video/" . $song['file_video']);
        }

        // Delete from DB
        $delete_query = "DELETE FROM songs WHERE id = $id";
        if(mysqli_query($conn, $delete_query)) {
            $_SESSION['success_msg'] = "Track deleted successfully.";
        }
    }
}

header("Location: index.php");
exit();
?>
