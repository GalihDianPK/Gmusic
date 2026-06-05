<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['playlist_name'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['playlist_name']));
    $user_id = intval($_SESSION['user_id']);
    
    $query = "INSERT INTO playlists (user_id, name) VALUES ($user_id, '$name')";
    if (mysqli_query($conn, $query)) {
        $playlist_id = mysqli_insert_id($conn);
        header("Location: playlist.php?id=" . $playlist_id);
        exit();
    }
}
?>
<?php require 'header.php'; ?>
<div class="flex items-center justify-center w-full h-full min-h-[500px]">
    <div class="bg-card-dark p-8 rounded-xl shadow-2xl max-w-lg w-full border border-white/5 mx-4">
        <h2 class="text-3xl font-bold text-white mb-6 text-center">Name your playlist</h2>
        <form action="create_playlist.php" method="POST" class="space-y-6">
            <input type="text" name="playlist_name" autofocus required placeholder="My Playlist #1" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded-md p-4 text-white text-lg placeholder:text-slate-500">
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-primary hover:scale-105 text-black font-bold py-3 px-8 rounded-full transition-transform">Create</button>
                <a href="index.php" class="flex-1 bg-transparent text-center border border-slate-500 text-slate-300 hover:text-white hover:border-white font-bold py-3 px-8 rounded-full transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php require 'footer.php'; ?>
