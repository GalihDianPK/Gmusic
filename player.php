<?php
require 'header.php';
require 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "<div class='text-white p-6'>Song ID not provided.</div>";
    require 'footer.php';
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM songs WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='text-white p-6'>Song not found.</div>";
    require 'footer.php';
    exit();
}

$song = mysqli_fetch_assoc($result);
$cover = current(explode("?", $song['cover_image'] ? $song['cover_image'] : 'default_cover.jpg'));
$has_video = !empty($song['file_video']);
?>

<div class="w-full flex-1 flex flex-col pt-4">
    
    <!-- Header Banner -->
    <div class="flex items-end gap-6 p-6 pb-8 border-b border-white/10 shrink-0">
        <div class="w-48 h-48 md:w-56 md:h-56 shadow-2xl shrink-0 bg-card-dark rounded-md overflow-hidden relative" id="detail-cover-container">
            <img src="uploads/covers/<?php echo htmlspecialchars($cover); ?>" class="w-full h-full object-cover" id="detail-cover">
        </div>
        
        <div class="flex flex-col text-white justify-end relative z-10 w-full overflow-hidden">
            <span class="text-xs md:text-sm font-bold tracking-widest uppercase mb-2">
                <?php echo $has_video ? 'Music Video' : 'Song'; ?>
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-black mb-6 tracking-tighter truncate max-w-full leading-none" id="detail-title">
                <?php echo htmlspecialchars($song['title']); ?>
            </h1>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                    <span class="material-symbols-outlined text-sm">person</span>
                </div>
                <span class="font-bold hover:underline cursor-pointer text-sm md:text-base mr-1" id="detail-artist"><?php echo htmlspecialchars($song['artist']); ?></span>
                <span class="text-sm text-slate-300">•</span>
                <span class="text-sm text-slate-300 font-medium"><?php echo htmlspecialchars($song['genre'] ?: 'Unknown Genre'); ?></span>
            </div>
        </div>
    </div>

    <!-- Toggle & Buttons Bar -->
    <div class="px-6 py-6 flex items-center justify-between shrink-0 sticky top-0 bg-card-dark/95 backdrop-blur-md z-20">
        <div class="flex items-center gap-6">
            <button class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-black hover:scale-105 transition-transform shadow-xl"
                    onclick="triggerPlayThis()">
                <span class="material-symbols-outlined text-4xl" id="detail-play-icon">play_arrow</span>
            </button>
            <button class="text-slate-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-4xl">favorite_border</span>
            </button>
            <button class="text-slate-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-4xl">more_horiz</span>
            </button>
        </div>

        <?php if ($has_video): ?>
        <div class="bg-black/50 p-1 rounded-full flex items-center shadow-inner">
            <button id="btn-toggle-audio" class="px-6 py-2 rounded-full text-sm font-bold bg-[#333] text-white transition-colors" onclick="switchView('audio')">Lyrics</button>
            <button id="btn-toggle-video" class="px-6 py-2 rounded-full text-sm font-bold text-slate-400 hover:text-white transition-colors" onclick="switchView('video')">Video</button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Content Pane: Video vs Lyrics -->
    <div class="px-6 pb-24 flex-1">
        
        <?php if ($has_video): ?>
        <!-- VIDEO VIEW -->
        <div id="view-video" class="hidden w-full max-w-4xl mx-auto rounded-xl overflow-hidden shadow-2xl bg-black relative group">
            <video id="detail-video-player" class="w-full max-h-[60vh] object-cover brightness-50 group-hover:brightness-100 transition-all duration-500" poster="uploads/covers/<?php echo htmlspecialchars($cover); ?>" loop playsinline>
                <source src="uploads/video/<?php echo htmlspecialchars($song['file_video']); ?>" type="video/mp4">
            </video>
            <!-- Custom Video Overlay or controls could go here, for now using standard or no controls relying on global play/pause -->
        </div>
        <?php endif; ?>

        <!-- AUDIO/LYRICS VIEW -->
        <div id="view-audio" class="w-full">
            <h2 class="text-2xl font-bold text-white mb-6">Lyrics</h2>
            <div class="max-w-3xl bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                <div class="text-2xl font-bold leading-relaxed text-slate-300 pointer-events-none" id="detail-lyrics" style="line-height: 1.8;">
                    <?php 
                    if (!empty($song['lyrics'])) {
                        echo nl2br(htmlspecialchars($song['lyrics']));
                    } else {
                        echo "♪<br><br>(Lyrics unavailable for this track)<br>Enjoy the music!<br><br>♪";
                    }
                    ?>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- JS specifically to link player.php details to the global queue system -->
<script>
    const currentTrackData = {
        id: <?php echo $song['id']; ?>,
        title: <?php echo json_encode($song['title']); ?>,
        artist: <?php echo json_encode($song['artist']); ?>,
        coverUrl: <?php echo json_encode($cover); ?>,
        audioUrl: <?php echo json_encode($song['file_audio']); ?>,
        lyrics: <?php echo json_encode($song['lyrics']); ?>
    };

    function triggerPlayThis() {
        if (typeof window.playQueue === 'function') {
            window.playQueue([currentTrackData], 0);
        }
    }

    // This callback is triggered by footer.php whenever a new track loads 
    // so this page updates its UI dynamically if someone skips tracks via footer.
    window.syncPlayerPageUI = function(track) {
        document.getElementById('detail-title').innerText = track.title;
        document.getElementById('detail-artist').innerText = track.artist;
        document.getElementById('detail-cover').src = 'uploads/covers/' + track.coverUrl;
        
        const lyricsContainer = document.getElementById('detail-lyrics');
        if(track.lyrics && track.lyrics.trim() !== "") {
            lyricsContainer.innerHTML = track.lyrics.replace(/\n/g, "<br>");
        } else {
            lyricsContainer.innerHTML = "♪<br><br>(Lyrics unavailable for this track)<br>Enjoy the music!<br><br>♪";
        }
    };

    // Video vs Lyrics Toggle Logic
    const viewAudio = document.getElementById('view-audio');
    const viewVideo = document.getElementById('view-video');
    const btnAudio = document.getElementById('btn-toggle-audio');
    const btnVideo = document.getElementById('btn-toggle-video');
    const globalAudioPlayer = document.getElementById('main-audio-player');
    const localVideoPlayer = document.getElementById('detail-video-player');

    function switchView(mode) {
        if (!viewVideo) return; // No video available
        
        if (mode === 'video') {
            viewAudio.classList.add('hidden');
            viewVideo.classList.remove('hidden');
            
            btnAudio.classList.remove('bg-[#333]', 'text-white');
            btnAudio.classList.add('text-slate-400');
            
            btnVideo.classList.add('bg-[#333]', 'text-white');
            btnVideo.classList.remove('text-slate-400');

            // Pause global audio, play local video
            if (globalAudioPlayer && !globalAudioPlayer.paused) {
                globalAudioPlayer.pause();
                window.isPlaying = false;
                document.getElementById('play-pause-icon').textContent = 'play_arrow';
            }
            if (localVideoPlayer) {
                // Sync time just roughly if same track
                // localVideoPlayer.currentTime = globalAudioPlayer.currentTime;
                localVideoPlayer.play();
                localVideoPlayer.setAttribute("controls", "controls");
            }
        } else {
            viewVideo.classList.add('hidden');
            viewAudio.classList.remove('hidden');
            
            btnVideo.classList.remove('bg-[#333]', 'text-white');
            btnVideo.classList.add('text-slate-400');
            
            btnAudio.classList.add('bg-[#333]', 'text-white');
            btnAudio.classList.remove('text-slate-400');
            
            // Pause local video
            if (localVideoPlayer) localVideoPlayer.pause();
        }
    }
</script>

<?php require 'footer.php'; ?>
