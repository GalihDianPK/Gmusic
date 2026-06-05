<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['song_id'])) {
    header("Location: index.php");
    exit();
}

$song_id = intval($_GET['song_id']);
$user_id = intval($_SESSION['user_id']);

// Handle addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlist_id'])) {
    $playlist_id = intval($_POST['playlist_id']);
    
    // Verify owner
    $check_q = "SELECT id FROM playlists WHERE id = $playlist_id AND user_id = $user_id";
    if (mysqli_num_rows(mysqli_query($conn, $check_q)) > 0) {
        $insert_q = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES ($playlist_id, $song_id)";
        mysqli_query($conn, $insert_q);
        header("Location: playlist.php?id=" . $playlist_id);
        exit();
    }
}

// Fetch user's playlists
$playlists_query = mysqli_query($conn, "SELECT * FROM playlists WHERE user_id = $user_id ORDER BY created_at DESC");

// Fetch song info
$song_query = mysqli_query($conn, "SELECT title, artist FROM songs WHERE id = $song_id");
$song = mysqli_fetch_assoc($song_query);
?>
<?php require 'header.php'; ?>
<div class="h-full flex items-center justify-center -mt-20">
    <div class="bg-card-dark p-8 rounded-xl shadow-2xl max-w-md w-full border border-white/5">
        <h2 class="text-2xl font-bold text-white mb-2 text-center">Add to playlist</h2>
        <p class="text-sm text-slate-400 mb-6 text-center">Adding "<?php echo htmlspecialchars($song['title']); ?>"</p>
        
        <?php if (mysqli_num_rows($playlists_query) > 0): ?>
            <form action="add_to_playlist.php?song_id=<?php echo $song_id; ?>" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-300">Choose a playlist</label>
                    <select name="playlist_id" required class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white">
                        <?php while($pl = mysqli_fetch_assoc($playlists_query)): ?>
                            <option value="<?php echo $pl['id']; ?>"><?php echo htmlspecialchars($pl['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-primary hover:scale-105 text-black font-bold py-3 px-8 rounded-full transition-transform">Add</button>
                    <button type="button" onclick="history.back()" class="flex-1 bg-transparent text-center border border-slate-500 text-slate-300 hover:text-white hover:border-white font-bold py-3 px-8 rounded-full transition-colors">Cancel</button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center text-slate-300 mb-6">
                You don't have any playlists yet!
            </div>
            <a href="create_playlist.php" class="block w-full bg-primary text-center text-black font-bold py-3 px-8 rounded-full transition-transform hover:scale-105">
                Create new playlist
            </a>
            <button type="button" onclick="history.back()" class="mt-4 block w-full bg-transparent text-center border border-slate-500 text-slate-300 hover:text-white hover:border-white font-bold py-3 px-8 rounded-full transition-colors">Cancel</button>
        <?php endif; ?>
    </div>
</div>
<?php require 'footer.php'; ?>
