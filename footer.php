<?php
if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
    return;
}
?>
                </div> <!-- End Inner P-6 Wrapper -->
            </div> <!-- End Scrollable Page Content -->
        </main> <!-- End Main -->

        <!-- Right Sidebar (Mini Video Preview) -->
        <aside class="w-[280px] bg-black border-l border-[#282828] flex-col hidden lg:flex relative overflow-hidden shrink-0 z-40 p-4" id="right-sidebar">
            <h3 class="text-white font-bold mb-4 flex items-center justify-between">
                <span>Now Playing View</span>
                <span class="material-symbols-outlined text-slate-400 text-sm">close</span>
            </h3>
            <div class="w-full bg-[#181818] rounded-xl overflow-hidden shadow-2xl relative group">
                <!-- Cover Fallback / Loading -->
                <img src="uploads/covers/default_cover.jpg" id="mini-cover" class="w-full aspect-square object-cover opacity-50 hidden">
                
                <!-- Video Element -->
                <video id="mini-video" class="w-full h-auto object-cover hidden brightness-50 group-hover:brightness-100 transition-all duration-500" muted loop playsinline></video>
                
                <div class="p-4" id="mini-info">
                    <h4 class="text-lg font-bold text-white truncate" id="mini-title">Select a track</h4>
                    <p class="text-sm text-slate-400 truncate" id="mini-artist">-</p>
                </div>
            </div>
            <div class="mt-4 p-4 bg-[#181818] rounded-xl h-48 border border-white/5 flex flex-col items-center justify-center text-center">
                 <span class="material-symbols-outlined text-4xl text-slate-500 mb-2">lyrics</span>
                 <p class="text-sm text-slate-400 font-bold mb-2">Looking for lyrics?</p>
                 <a href="#" class="text-xs border border-slate-500 px-4 py-1 rounded-full text-white hover:border-white transition-colors" onclick="toggleLyricsPanel()">Open Player</a>
            </div>
        </aside>

    </div> <!-- End Main Top Section -->

    <!-- Mobile Navigation Bar (Only visible on small screens) -->
    <nav class="md:hidden bg-[#181818] border-t border-[#282828] h-[60px] flex items-center justify-around shrink-0 w-full z-40">
        <a href="index.php" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-white' : ''; ?>">
            <span class="material-symbols-outlined text-2xl" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>home</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="search.php" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'search.php' ? 'text-white' : ''; ?>">
            <span class="material-symbols-outlined text-2xl" <?php echo basename($_SERVER['PHP_SELF']) == 'search.php' ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>search</span>
            <span class="text-[10px] font-medium">Search</span>
        </a>
        <a href="liked.php" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'liked.php' ? 'text-white' : ''; ?>">
            <span class="material-symbols-outlined text-2xl" <?php echo basename($_SERVER['PHP_SELF']) == 'liked.php' ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>favorite</span>
            <span class="text-[10px] font-medium">Liked</span>
        </a>
        <a href="create_playlist.php" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'create_playlist.php' ? 'text-white' : ''; ?>">
            <span class="material-symbols-outlined text-2xl" <?php echo basename($_SERVER['PHP_SELF']) == 'create_playlist.php' ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>add_box</span>
            <span class="text-[10px] font-medium">Playlist</span>
        </a>
    </nav>

    <!-- Global Player Bar (Bottom) -->
    <footer class="h-[70px] md:h-[90px] bg-black border-t border-[#282828] flex items-center justify-between px-2 md:px-4 z-50 relative shrink-0 w-full" id="global-player-bar">
        
        <!-- Now Playing Track Info -->
        <div class="flex items-center gap-2 md:gap-4 w-1/2 md:w-[30%] min-w-0 md:min-w-[180px] overflow-hidden">
            <div class="w-10 h-10 md:w-14 md:h-14 bg-card-hover rounded flex-shrink-0 overflow-hidden" id="footer-cover-container">
                <img src="uploads/covers/default_cover.jpg" alt="Cover" class="w-full h-full object-cover hidden" id="footer-cover">
                <div class="w-full h-full flex items-center justify-center text-slate-500" id="footer-cover-placeholder">
                    <span class="material-symbols-outlined text-sm md:text-xl">music_note</span>
                </div>
            </div>
            <div class="flex flex-col justify-center min-w-0 overflow-hidden pr-2 flex-grow">
                <a href="#" class="text-sm font-bold text-white hover:underline truncate w-full" id="footer-title">No Track</a>
                <a href="#" class="text-xs text-slate-400 hover:underline hover:text-white truncate w-full" id="footer-artist">-</a>
            </div>
            <button class="text-slate-400 hover:text-white transition-colors hidden sm:block shrink-0" id="btn-favorite" onclick="if(queue[currentIndex]) window.toggleLike(queue[currentIndex].id, this, event)">
                <span class="material-symbols-outlined text-xl">favorite_border</span>
            </button>
        </div>

        <!-- Player Controls -->
        <div class="flex flex-row md:flex-col items-center justify-end md:justify-center w-1/2 md:max-w-[40%] md:w-full px-2 md:px-4 shrink-0 gap-4 md:gap-0">
            <div class="flex items-center gap-4 md:gap-6 md:mb-2">
                <button class="text-slate-400 hover:text-white transition-colors hidden md:block" onclick="toggleShuffle()" title="Shuffle">
                    <span class="material-symbols-outlined text-xl" id="shuffle-icon">shuffle</span>
                </button>
                <button class="text-slate-400 hover:text-white transition-colors hidden md:block" onclick="playPrev()" title="Previous">
                    <span class="material-symbols-outlined text-2xl" id="prev-btn">skip_previous</span>
                </button>
                
                <!-- PLAY / PAUSE -->
                <button class="w-10 h-10 md:w-8 md:h-8 rounded-full bg-white flex items-center justify-center text-black hover:scale-105 transition-transform" onclick="togglePlayPause()" id="play-pause-btn">
                    <span class="material-symbols-outlined" id="play-pause-icon">play_arrow</span>
                </button>
                
                <button class="text-slate-400 hover:text-white transition-colors hidden sm:block md:block" onclick="playNext()" title="Next">
                    <span class="material-symbols-outlined text-2xl" id="next-btn">skip_next</span>
                </button>
                <button class="text-slate-400 hover:text-white transition-colors hidden md:block" onclick="toggleRepeat()" title="Repeat">
                    <span class="material-symbols-outlined text-xl" id="repeat-icon">repeat</span>
                </button>
            </div>
            
            <div class="hidden md:flex items-center gap-2 w-full max-w-md">
                <span class="text-xs text-slate-400 min-w-[40px] text-right" id="current-time">0:00</span>
                
                <!-- Progress Bar -->
                <div class="h-1 flex-1 bg-white/30 rounded-full cursor-pointer group relative" id="progress-container">
                    <div class="h-full bg-white group-hover:bg-primary rounded-full relative w-0" id="progress-bar">
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 shadow"></div>
                    </div>
                </div>
                
                <span class="text-xs text-slate-400 min-w-[40px]" id="total-time">0:00</span>
            </div>
        </div>

        <!-- Right Controls (Volume, etc) -->
        <div class="hidden md:flex items-center justify-end gap-2 w-[30%] min-w-[180px]">
            <button class="text-slate-400 hover:text-white transition-colors" title="Lyrics" onclick="toggleLyricsPanel()">
                <span class="material-symbols-outlined text-xl" id="lyrics-icon">lyrics</span>
            </button>
            <button class="text-slate-400 hover:text-white transition-colors" title="Queue">
                <span class="material-symbols-outlined text-xl">queue_music</span>
            </button>
            
            <div class="flex items-center gap-2 ml-2 w-24">
                <button class="text-slate-400 hover:text-white transition-colors" onclick="toggleMute()">
                    <span class="material-symbols-outlined text-xl" id="volume-icon">volume_up</span>
                </button>
                <div class="h-1 flex-1 bg-white/30 rounded-full cursor-pointer group relative" id="volume-container">
                    <div class="h-full bg-white group-hover:bg-primary rounded-full relative w-full" id="volume-bar">
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Hidden HTML5 Audio Element -->
    <audio id="main-audio-player" autoplay></audio>

    <script>
        // Global Audio Player Variables
        const audioPlayer = document.getElementById('main-audio-player');
        const playPauseBtn = document.getElementById('play-pause-btn');
        const playPauseIcon = document.getElementById('play-pause-icon');
        const progressBar = document.getElementById('progress-bar');
        const progressContainer = document.getElementById('progress-container');
        const currentTimeEl = document.getElementById('current-time');
        const totalTimeEl = document.getElementById('total-time');
        
        // Mini Video Elements
        const miniVideo = document.getElementById('mini-video');
        const miniCover = document.getElementById('mini-cover');
        
        let isPlaying = false;
        let shuffleMode = false;
        let repeatMode = 0; 
        
        let queue = [];
        let originalQueue = [];
        let currentIndex = -1;

        // AJAX Toggle Like
        window.toggleLike = function(songId, element, event) {
            if(event) event.stopPropagation();
            fetch('toggle_like.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'song_id=' + songId
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const icon = element.tagName.toLowerCase() === 'span' ? element : element.querySelector('.material-symbols-outlined');
                    if(data.action === 'liked') {
                        icon.style.fontVariationSettings = "'FILL' 1";
                        icon.classList.add('text-primary');
                    } else {
                        icon.style.fontVariationSettings = "'FILL' 0";
                        icon.classList.remove('text-primary');
                    }
                }
            });
        };

        window.playQueue = function(tracksArray, startIndex = 0) {
            originalQueue = [...tracksArray];
            queue = [...tracksArray];
            
            if (shuffleMode) {
                const startTrack = queue.splice(startIndex, 1)[0];
                for (let i = queue.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [queue[i], queue[j]] = [queue[j], queue[i]];
                }
                queue.unshift(startTrack);
                currentIndex = 0;
            } else {
                currentIndex = startIndex;
            }
            
            loadTrackIntoPlayer(queue[currentIndex]);
        };

        function loadTrackIntoPlayer(track) {
            if(!track) return;
            
            // Footer UI
            const titleEl = document.getElementById('footer-title');
            titleEl.textContent = track.title;
            titleEl.href = "player.php?id=" + track.id;
            document.getElementById('footer-artist').textContent = track.artist;
            
            // Mini Right Side UI
            document.getElementById('mini-title').textContent = track.title;
            document.getElementById('mini-artist').textContent = track.artist;
            
            const coverImg = document.getElementById('footer-cover');
            const placeholder = document.getElementById('footer-cover-placeholder');
            const coverUrlFull = track.coverUrl ? ('uploads/covers/' + track.coverUrl) : 'uploads/covers/default_cover.jpg';
            
            if (track.coverUrl && track.coverUrl !== '') {
                coverImg.src = coverUrlFull;
                coverImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
                miniCover.src = coverUrlFull;
            } else {
                coverImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
                miniCover.src = 'uploads/covers/default_cover.jpg';
            }

            // Sync with Player Page or navigate to it
            navigateTo("player.php?id=" + track.id);

            // Fetch song details via JS or just assume we don't have video URL directly in queue unless explicitly added from API
            // Wait, tracksArray now does not predictably have file_video unless I update index.php to fetch it.
            // Let's do an async fetch to get info including video and if liked
            fetch('api_song.php?id=' + track.id)
                .then(r => r.json())
                .then(d => {
                    // Update Like Status globally
                    const favIcon = document.querySelector('#btn-favorite .material-symbols-outlined');
                    if(d.is_liked) {
                         favIcon.style.fontVariationSettings = "'FILL' 1";
                         favIcon.classList.add('text-primary');
                    } else {
                         favIcon.style.fontVariationSettings = "'FILL' 0";
                         favIcon.classList.remove('text-primary');
                    }

                    // Mini Video Logic
                    if(d.file_video) {
                        miniVideo.src = 'uploads/video/' + d.file_video;
                        miniVideo.classList.remove('hidden');
                        miniCover.classList.add('hidden');
                        if(!audioPlayer.paused) miniVideo.play().catch(e=>console.log(e));
                    } else {
                        miniVideo.classList.add('hidden');
                        miniVideo.pause();
                        miniCover.classList.remove('hidden');
                    }
                })
                .catch(e => {
                    miniVideo.classList.add('hidden');
                    miniCover.classList.remove('hidden');
                });

            // Load & Play Audio
            audioPlayer.src = 'uploads/audio/' + track.audioUrl;
            let playPromise = audioPlayer.play();
            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    isPlaying = true;
                    playPauseIcon.textContent = 'pause';
                    if(!miniVideo.classList.contains('hidden')) miniVideo.play()
                }).catch(error => {
                    console.log("Autoplay prevented:", error);
                    isPlaying = false;
                    playPauseIcon.textContent = 'play_arrow';
                });
            }
        }

        window.playNext = function() {
            if(queue.length === 0) return;
            if(repeatMode === 2) {
                audioPlayer.currentTime = 0;
                audioPlayer.play();
                return;
            }
            currentIndex++;
            if (currentIndex >= queue.length) {
                if (repeatMode === 1) currentIndex = 0; 
                else {
                    currentIndex = queue.length - 1; 
                    audioPlayer.pause();
                    isPlaying = false;
                    playPauseIcon.textContent = 'play_arrow';
                    return; 
                }
            }
            loadTrackIntoPlayer(queue[currentIndex]);
        };

        window.playPrev = function() {
            if(queue.length === 0) return;
            if (audioPlayer.currentTime > 3) { audioPlayer.currentTime = 0; return; }
            currentIndex--;
            if (currentIndex < 0) currentIndex = 0;
            loadTrackIntoPlayer(queue[currentIndex]);
        };

        audioPlayer.addEventListener('ended', () => { playNext(); });
 
        window.togglePlayPause = function() {
            if (!audioPlayer.src || audioPlayer.src.endsWith('/null') || audioPlayer.src === window.location.href) return;
            if (audioPlayer.paused) {
                audioPlayer.play().catch(e => console.log(e));
            } else {
                audioPlayer.pause();
            }
        };

        audioPlayer.addEventListener('play', () => {
            isPlaying = true;
            playPauseIcon.textContent = 'pause';
            if(!miniVideo.classList.contains('hidden')) miniVideo.play().catch(e => console.log(e));
            const detailVideo = document.getElementById('detail-video-player');
            if (detailVideo) {
                detailVideo.muted = true;
                detailVideo.play().catch(e => console.log(e));
            }
        });

        audioPlayer.addEventListener('pause', () => {
            isPlaying = false;
            playPauseIcon.textContent = 'play_arrow';
            miniVideo.pause();
            const detailVideo = document.getElementById('detail-video-player');
            if (detailVideo) detailVideo.pause();
        });
 
        audioPlayer.addEventListener('timeupdate', () => {
            const current = audioPlayer.currentTime;
            const duration = audioPlayer.duration;
            currentTimeEl.textContent = formatTime(current);
            if (!isNaN(duration)) {
                totalTimeEl.textContent = formatTime(duration);
                const progressPercent = (current / duration) * 100;
                progressBar.style.width = `${progressPercent}%`;
            }
            // Roughly sync video time just in case, but usually just letting them play parallel is smoother
        });

        progressContainer.addEventListener('click', (e) => {
            const width = progressContainer.clientWidth;
            const duration = audioPlayer.duration;
            if (!isNaN(duration)) {
                let jumpTime = (e.offsetX / width) * duration;
                audioPlayer.currentTime = jumpTime;
                miniVideo.currentTime = jumpTime; // Sync video
            }
        });

        function formatTime(seconds) {
            if (isNaN(seconds) || !isFinite(seconds)) return "0:00";
            const min = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60);
            return `${min}:${sec < 10 ? '0' : ''}${sec}`;
        }

        const volumeContainer = document.getElementById('volume-container');
        const volumeBar = document.getElementById('volume-bar');
        const volumeIcon = document.getElementById('volume-icon');

        volumeContainer.addEventListener('click', (e) => {
            let percent = e.offsetX / volumeContainer.clientWidth;
            if (percent < 0) percent = 0;
            if (percent > 1) percent = 1;
            audioPlayer.volume = percent;
            volumeBar.style.width = `${percent * 100}%`;
            if (percent === 0) volumeIcon.textContent = 'volume_off';
            else if (percent < 0.5) volumeIcon.textContent = 'volume_down';
            else volumeIcon.textContent = 'volume_up';
        });

        window.toggleMute = function() {
            if (audioPlayer.muted) {
                audioPlayer.muted = false;
                if(audioPlayer.volume === 0) audioPlayer.volume = 0.5;
                volumeIcon.textContent = audioPlayer.volume > 0.5 ? 'volume_up' : 'volume_down';
                volumeBar.style.width = `${audioPlayer.volume * 100}%`;
            } else {
                audioPlayer.muted = true;
                volumeIcon.textContent = 'volume_off';
                volumeBar.style.width = '0%';
            }
        };

        window.toggleShuffle = function() {
            shuffleMode = !shuffleMode;
            const icon = document.getElementById('shuffle-icon');
            if (shuffleMode) {
                icon.classList.add('text-primary');
                icon.classList.remove('text-slate-400');
                if(queue.length > 0) {
                    const currentTrack = queue[currentIndex];
                    let newQueue = [...queue];
                    for (let i = newQueue.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [newQueue[i], newQueue[j]] = [newQueue[j], newQueue[i]];
                    }
                    const trackIdx = newQueue.indexOf(currentTrack);
                    if(trackIdx !== -1) { newQueue.splice(trackIdx, 1); newQueue.unshift(currentTrack); }
                    queue = newQueue;
                    currentIndex = 0;
                }
            } else {
                icon.classList.remove('text-primary');
                icon.classList.add('text-slate-400');
                if(queue.length > 0) {
                    const currentTrack = queue[currentIndex];
                    queue = [...originalQueue];
                    currentIndex = queue.findIndex(t => t.id === currentTrack.id);
                    if(currentIndex === -1) currentIndex = 0;
                }
            }
        };
        
        window.toggleRepeat = function() {
            repeatMode = (repeatMode + 1) % 3;
            const icon = document.getElementById('repeat-icon');
            if (repeatMode === 0) {
                icon.classList.remove('text-primary');
                icon.classList.add('text-slate-400');
                icon.textContent = 'repeat';
            } else if (repeatMode === 1) {
                icon.classList.add('text-primary');
                icon.classList.remove('text-slate-400');
                icon.textContent = 'repeat'; 
            } else if (repeatMode === 2) {
                icon.classList.add('text-primary');
                icon.classList.remove('text-slate-400');
                icon.textContent = 'repeat_one'; 
            }
        };

        window.toggleLyricsPanel = function() {
            const t = document.getElementById('footer-title').getAttribute('href');
            if(t && !t.endsWith('#') && t !== '#') navigateTo(t);
        };

        // SPA Page Router & Script Executer
        function navigateTo(url, pushState = true) {
            if (!url || url.includes('logout.php') || url.includes('admin/') || (url.startsWith('http') && !url.startsWith(window.location.origin))) {
                window.location.href = url;
                return;
            }

            const ajaxUrl = url.includes('?') ? (url + '&ajax=1') : (url + '?ajax=1');
            fetch(ajaxUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                if (!res.ok) throw new Error('Not OK');
                return res.text();
            })
            .then(html => {
                const container = document.querySelector('main .overflow-y-auto > div');
                if (container) {
                    container.innerHTML = html;
                    
                    // Execute script tags in the new content
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                        } else {
                            newScript.textContent = oldScript.textContent;
                        }
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });

                    // Scroll to top
                    document.querySelector('main .overflow-y-auto').scrollTop = 0;

                    // Update browser history
                    if (pushState) {
                        history.pushState({ url: url }, '', url);
                    }

                    // Update sidebar active styling
                    updateSidebarActiveStyle(url);
                }
            })
            .catch(err => {
                console.warn('Navigation failed via AJAX, falling back to full reload:', err);
                window.location.href = url;
            });
        }

        function updateSidebarActiveStyle(url) {
            const path = url.split('/').pop().split('?')[0] || 'index.php';
            const sidebarLinks = document.querySelectorAll('aside nav a');
            sidebarLinks.forEach(link => {
                const linkHref = link.getAttribute('href');
                if (linkHref === path) {
                    link.classList.add('text-white');
                    link.classList.remove('text-slate-300');
                } else {
                    link.classList.remove('text-white');
                    link.classList.add('text-slate-300');
                }
            });
        }

        // Intercept link clicks
        document.addEventListener('click', function(e) {
            const anchor = e.target.closest('a');
            if (!anchor) return;
            
            const href = anchor.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || anchor.getAttribute('target') === '_blank') return;
            
            if (href.includes('logout.php') || href.includes('admin/') || (href.startsWith('http') && !href.startsWith(window.location.origin))) {
                return;
            }
            
            e.preventDefault();
            navigateTo(href);
        });

        // Intercept search form submit (GET)
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('form');
            if (!form) return;
            
            const action = form.getAttribute('action') || window.location.pathname;
            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            
            if (method === 'GET' && !action.includes('logout.php') && !action.includes('admin/')) {
                e.preventDefault();
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();
                const url = action + (action.includes('?') ? '&' : '?') + params;
                navigateTo(url);
            }
        });

        // Handle back/forward buttons
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) {
                navigateTo(e.state.url, false);
            } else {
                navigateTo(window.location.pathname + window.location.search, false);
            }
        });

        // Initialize state
        if (!history.state) {
            history.replaceState({ url: window.location.pathname + window.location.search }, '', window.location.href);
        }
    </script>
</body>
</html>
