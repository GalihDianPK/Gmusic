<?php
require 'header.php';
require 'koneksi.php';

// Fetch greetings based on time
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good morning";
} elseif ($hour < 18) {
    $greeting = "Good afternoon";
} else {
    $greeting = "Good evening";
}
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
$greeting .= ", " . $username;

// Fetch newest songs
$query = "SELECT * FROM songs ORDER BY created_at DESC LIMIT 12";
$result = mysqli_query($conn, $query);

$recent_tracks = [];
while($row = mysqli_fetch_assoc($result)) {
    $recent_tracks[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'artist' => $row['artist'],
        'coverUrl' => current(explode("?", $row['cover_image'] ? $row['cover_image'] : 'default_cover.jpg')),
        'audioUrl' => $row['file_audio'],
        'lyrics' => $row['lyrics']
    ];
}

// Fetch Quick picks 
$result_quick = mysqli_query($conn, "SELECT * FROM songs ORDER BY RAND() LIMIT 6");
$quick_tracks = [];
while($row = mysqli_fetch_assoc($result_quick)) {
    $quick_tracks[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'artist' => $row['artist'],
        'coverUrl' => current(explode("?", $row['cover_image'] ? $row['cover_image'] : 'default_cover.jpg')),
        'audioUrl' => $row['file_audio'],
        'lyrics' => $row['lyrics']
    ];
}
?>

<script>
    const quickTracksQueue = <?php echo json_encode($quick_tracks); ?>;
    const recentTracksQueue = <?php echo json_encode($recent_tracks); ?>;
</script>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-white mb-6"><?php echo $greeting; ?></h2>
    
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-8">
        <?php foreach($quick_tracks as $index => $song): ?>
        <div class="bg-white/5 hover:bg-white/20 transition-colors rounded overflow-hidden flex items-center cursor-pointer group relative h-16 sm:h-20 shadow-sm hover:shadow-md"
             onclick="playQueue(quickTracksQueue, <?php echo $index; ?>)">
            <div class="h-full aspect-square bg-card-dark shrink-0 relative z-10 shadow-[4px_0_12px_rgba(0,0,0,0.3)]">
                <img src="uploads/covers/<?php echo htmlspecialchars($song['coverUrl']); ?>" class="w-full h-full object-cover">
            </div>
            
            <div class="flex-1 min-w-0 px-3 md:px-4">
                <h3 class="font-bold text-white truncate text-sm sm:text-base leading-tight"><?php echo htmlspecialchars($song['title']); ?></h3>
            </div>

            <button class="absolute right-2 md:right-4 w-10 h-10 md:w-12 md:h-12 bg-primary rounded-full flex items-center justify-center text-black shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-105 z-20" 
                    onclick="event.stopPropagation(); playQueue(quickTracksQueue, <?php echo $index; ?>)">
                <span class="material-symbols-outlined text-2xl md:text-3xl ml-1">play_arrow</span>
            </button>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Recently Added Section -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white hover:underline cursor-pointer">Recently Added</h2>
        <a href="#" class="text-sm font-bold text-slate-400 hover:text-white uppercase tracking-wider">Show all</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
        <?php foreach($recent_tracks as $index => $song): ?>
        <div class="bg-[#181818] hover:bg-[#282828] p-4 rounded-lg transition-colors cursor-pointer group relative flex flex-col items-center"
             onclick="playQueue(recentTracksQueue, <?php echo $index; ?>)">
            
            <div class="w-full aspect-square rounded-md overflow-hidden mb-4 relative shadow-lg">
                <img src="uploads/covers/<?php echo htmlspecialchars($song['coverUrl']); ?>" class="w-full h-full object-cover">
                
                <a href="add_to_playlist.php?song_id=<?php echo $song['id']; ?>" class="absolute top-2 right-2 w-8 h-8 bg-black/60 rounded-full flex items-center justify-center text-white hover:bg-black hover:scale-110 transition-all opacity-0 group-hover:opacity-100 z-10" onclick="event.stopPropagation();" title="Add to Playlist">
                    <span class="material-symbols-outlined text-sm">add</span>
                </a>
                
                <button class="absolute top-12 right-2 w-8 h-8 bg-black/60 rounded-full flex items-center justify-center text-slate-300 hover:bg-black hover:text-white hover:scale-110 transition-all opacity-0 group-hover:opacity-100 z-10" onclick="window.toggleLike(<?php echo $song['id']; ?>, this, event)" title="Like">
                    <span class="material-symbols-outlined text-sm">favorite_border</span>
                </button>

                <button class="absolute bottom-2 right-2 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-black shadow-xl translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:scale-105 hover:bg-primary-hover"
                        onclick="event.stopPropagation(); playQueue(recentTracksQueue, <?php echo $index; ?>)">
                    <span class="material-symbols-outlined text-3xl ml-1">play_arrow</span>
                </button>
            </div>
            
            <div class="w-full flex-1 flex flex-col pb-2">
                <h3 class="font-bold text-white mb-1 truncate w-full"><?php echo htmlspecialchars($song['title']); ?></h3>
                <p class="text-sm text-slate-400 truncate w-full"><?php echo htmlspecialchars($song['artist']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'footer.php'; ?>
