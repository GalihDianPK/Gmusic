<?php
require 'header.php';
require 'koneksi.php';

$search_query = "";
$search_tracks = [];
$is_genre_search = false;
$display_title = "";

if (isset($_GET['q']) || isset($_GET['genre'])) {
    if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
        $search_query = trim($_GET['q']);
        $clean_query = mysqli_real_escape_string($conn, $search_query);
        $sql = "SELECT * FROM songs WHERE title LIKE '%$clean_query%' OR artist LIKE '%$clean_query%' ORDER BY title ASC";
        $display_title = 'Search results for "' . htmlspecialchars($search_query) . '"';
    } elseif (isset($_GET['genre'])) {
        $is_genre_search = true;
        $search_query = trim($_GET['genre']);
        $clean_query = mysqli_real_escape_string($conn, $search_query);
        $sql = "SELECT * FROM songs WHERE genre = '$clean_query' ORDER BY title ASC";
        $display_title = htmlspecialchars($search_query) . " Category";
    }
    
    if (!empty($search_query) || $is_genre_search) {
        $result_songs = mysqli_query($conn, $sql);
        if($result_songs) {
            while($row = mysqli_fetch_assoc($result_songs)) {
                $search_tracks[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'artist' => $row['artist'],
                    'coverUrl' => $row['cover_image'] ? $row['cover_image'] : 'default_cover.jpg',
                    'audioUrl' => $row['file_audio'],
                    'lyrics' => $row['lyrics']
                ];
            }
        }
    }
}
?>

<div class="mb-8">
    <!-- Inline Search Bar specific to search page -->
    <div class="mb-8 max-w-lg">
        <form action="search.php" method="GET" class="relative">
            <span class="absolute inset-y-0 left-4 flex items-center text-slate-800">
                <span class="material-symbols-outlined text-2xl">search</span>
            </span>
            <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="What do you want to listen to?" 
                   class="w-full h-12 pl-12 pr-4 rounded-full bg-white text-black font-semibold border-none focus:ring-2 focus:ring-white/50 text-base"
                   autofocus>
        </form>
    </div>

    <?php if (empty($search_query) && !$is_genre_search): ?>
        <h2 class="text-2xl font-bold text-white mb-6">Browse all</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            <?php 
            $genres = ['Pop', 'Rock', 'Hip-Hop', 'Jazz', 'Electronic', 'K-Pop'];
            $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-orange-500', 'bg-purple-500', 'bg-pink-500'];
            
            for ($i=0; $i<6; $i++) {
                echo '<a href="search.php?genre=' . urlencode($genres[$i]) . '" class="block ' . $colors[$i] . ' rounded-lg p-4 h-48 relative overflow-hidden cursor-pointer hover:scale-[1.02] transition-transform">';
                echo '<h3 class="font-bold text-xl text-white">' . $genres[$i] . '</h3>';
                echo '<div class="absolute -right-4 -bottom-4 w-24 h-24 bg-black/20 rotate-[25deg] shadow-lg"></div>';
                echo '</a>';
            }
            ?>
        </div>
    <?php else: ?>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white"><?php echo $display_title; ?></h2>
            <?php if ($is_genre_search): ?>
                 <a href="search.php" class="text-sm font-bold text-slate-300 hover:text-white transition-colors">Clear Filter</a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($search_tracks)): ?>
            <script>
                const searchResultsQueue = <?php echo json_encode($search_tracks); ?>;
            </script>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach($search_tracks as $index => $song): ?>
                <div class="flex items-center gap-4 hover:bg-white/10 p-2 rounded-md group transition-colors cursor-pointer relative"
                     onclick="playQueue(searchResultsQueue, <?php echo $index; ?>)">
                    
                    <div class="w-12 h-12 relative flex-shrink-0">
                        <img src="uploads/covers/<?php echo htmlspecialchars($song['coverUrl']); ?>" class="w-full h-full object-cover rounded">
                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center rounded">
                            <span class="material-symbols-outlined text-white">play_arrow</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col flex-1 truncate">
                        <span class="font-bold text-white truncate text-base"><?php echo htmlspecialchars($song['title']); ?></span>
                        <span class="text-sm text-slate-400 truncate"><?php echo htmlspecialchars($song['artist']); ?></span>
                    </div>
                    
                    <!-- Context menu trigger / Love -->
                    <div class="opacity-0 group-hover:opacity-100 px-4 flex gap-4 items-center">
                        <a href="add_to_playlist.php?song_id=<?php echo $song['id']; ?>" class="text-slate-400 hover:text-white transition-colors flex items-center tooltip" title="Add to Playlist" onclick="event.stopPropagation();">
                            <span class="material-symbols-outlined">playlist_add</span>
                        </a>
                        <span class="material-symbols-outlined text-slate-400 hover:text-white transition-colors hidden sm:block cursor-pointer" onclick="window.toggleLike(<?php echo $song['id']; ?>, this, event)">favorite_border</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center mt-12 mb-24 px-4 text-center">
                <h3 class="text-2xl font-bold text-white mb-4">No results found for "<?php echo htmlspecialchars($search_query); ?>"</h3>
                <p class="text-slate-400">Please make sure your words are spelled correctly or use less or different keywords.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>
