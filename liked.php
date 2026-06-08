<?php
require 'header.php';
require 'koneksi.php'; // Required here again just in case, though header has require_once

$user_id = intval($_SESSION['user_id']);

// Remove specific song from liked if initiated via a direct link (fallback if not AJAX)
if(isset($_GET['remove_song'])) {
    $song_id = intval($_GET['remove_song']);
    mysqli_query($conn, "DELETE FROM liked_songs WHERE user_id = $user_id AND song_id = $song_id LIMIT 1");
    echo "<script>window.location.href='liked.php';</script>";
    exit();
}

// Fetch liked songs
$songs_query = "
    SELECT songs.*, liked_songs.added_at 
    FROM songs 
    JOIN liked_songs ON songs.id = liked_songs.song_id 
    WHERE liked_songs.user_id = $user_id 
    ORDER BY liked_songs.added_at DESC
";
$songs_result = mysqli_query($conn, $songs_query);

$tracks_array = [];
while($row = mysqli_fetch_assoc($songs_result)) {
    $tracks_array[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'artist' => $row['artist'],
        'coverUrl' => current(explode("?", $row['cover_image'] ? $row['cover_image'] : 'default_cover.jpg')),
        'audioUrl' => $row['file_audio'],
        'lyrics' => $row['lyrics'] // Still fetching lyrics in case they want it later
    ];
}
?>

<div class="h-full flex flex-col relative w-full pt-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row items-center md:items-end gap-6 p-6 pb-8 border-b border-white/10 shrink-0 bg-gradient-to-b from-indigo-900 to-transparent">
        <div class="w-48 h-48 md:w-56 md:h-56 shadow-2xl shrink-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-md flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-8xl" style="font-variation-settings: 'FILL' 1;">favorite</span>
        </div>
        
        <div class="flex flex-col text-white justify-end relative z-10 w-full overflow-hidden text-center md:text-left">
            <span class="text-xs md:text-sm font-bold tracking-widest uppercase mb-2 hidden md:block">Playlist</span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-black mb-4 md:mb-6 tracking-tighter truncate max-w-full leading-none">
                Liked Songs
            </h1>
            <div class="flex items-center justify-center md:justify-start gap-2">
                <span class="font-bold cursor-pointer text-sm md:text-base mr-1"><?php echo $_SESSION['username']; ?></span>
                <span class="text-sm text-slate-300"> • <?php echo count($tracks_array); ?> songs</span>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="p-6 relative z-10 flex items-center gap-6">
        <button class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-black hover:scale-105 transition-transform shadow-xl"
                onclick="playLikedPlaylist()">
            <span class="material-symbols-outlined text-4xl">play_arrow</span>
        </button>
    </div>

    <!-- Song List -->
    <div class="px-6 pb-24 flex-1">
        <?php if (count($tracks_array) > 0): ?>
            <script>
                window.currentLikedQueue = <?php echo json_encode($tracks_array); ?>;
                function playLikedPlaylist() {
                    if (typeof window.playQueue === 'function') {
                        window.playQueue(window.currentLikedQueue, 0);
                    }
                }
            </script>
            <div class="w-full text-left text-slate-400 text-sm mb-4 border-b border-white/10 pb-2 grid grid-cols-12 px-2 md:px-4">
                <div class="col-span-1">#</div>
                <div class="col-span-6 md:col-span-8">Title</div>
                <div class="col-span-5 md:col-span-3 text-right pr-4 md:pr-6">Actions</div>
            </div>
            
            <div class="flex flex-col space-y-1">
            <?php foreach($tracks_array as $index => $song): ?>
                <div class="group grid grid-cols-12 items-center hover:bg-white/10 p-2 rounded-md transition-colors cursor-pointer"
                     onclick="window.playQueue(currentLikedQueue, <?php echo $index; ?>)">
                    <div class="col-span-1 text-slate-400 tabular-nums">
                        <span class="group-hover:hidden"><?php echo $index + 1; ?></span>
                        <span class="material-symbols-outlined text-white hidden group-hover:inline-block">play_arrow</span>
                    </div>
                    <div class="col-span-6 md:col-span-8 flex items-center gap-4">
                        <img src="uploads/covers/<?php echo htmlspecialchars($song['coverUrl']); ?>" class="w-10 h-10 object-cover rounded shadow">
                        <div class="flex flex-col truncate pr-2">
                            <span class="text-white font-medium truncate"><?php echo htmlspecialchars($song['title']); ?></span>
                            <span class="text-slate-400 text-sm truncate"><?php echo htmlspecialchars($song['artist']); ?></span>
                        </div>
                    </div>
                    <div class="col-span-5 md:col-span-3 flex items-center justify-end gap-4 opacity-0 group-hover:opacity-100 pr-2">
                        <a href="liked.php?remove_song=<?php echo $song['id']; ?>" class="text-primary hover:text-white transition-colors" title="Remove from Liked Songs" onclick="event.stopPropagation();">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center text-slate-400 pt-12">
                <span class="material-symbols-outlined text-6xl mb-4">favorite_border</span>
                <p class="text-lg">Songs you like will appear here</p>
                <a href="index.php" class="mt-4 px-6 py-2 bg-white text-black font-bold rounded-full hover:scale-105 transition-transform">Find songs</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>
