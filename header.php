<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
    return;
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GMusic</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📻</text></svg>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1db954",
                        "primary-hover": "#1ed760",
                        "background-dark": "#000000",
                        "card-dark": "#121212",
                        "card-hover": "#282828"
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    gridTemplateColumns: {
                        'layout': '280px 1fr',
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom Scrollbar for Spotify look */
        ::-webkit-scrollbar {
            width: 12px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #5a5a5a;
            border-radius: 6px;
            border: 3px solid #121212;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #b3b3b3;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-background-dark text-slate-100 font-display h-screen flex flex-col overflow-hidden">
    
    <!-- Main Top Section (Sidebar + Main Content) -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Left Sidebar (Hidden on Mobile) -->
        <aside class="hidden md:flex w-[280px] bg-background-dark flex-col p-2 gap-2 shrink-0">
            
            <!-- Logo & Main Nav -->
            <div class="bg-card-dark rounded-lg p-6 flex flex-col gap-5">
                <a href="index.php" class="flex items-center gap-2 text-white hover:text-white transition-colors">
                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                    </svg>
                    <span class="text-xl font-bold tracking-tight">GMusic</span>
                </a>
                
                <nav class="flex flex-col gap-4 mt-2">
                    <a href="index.php" class="flex items-center gap-4 text-slate-300 hover:text-white transition-colors font-bold <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-white' : ''; ?>">
                        <span class="material-symbols-outlined text-3xl">home</span>
                        Home
                    </a>
                    <a href="liked.php" class="flex items-center gap-4 text-slate-300 hover:text-white transition-colors font-bold group">
                        <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white flex items-center justify-center rounded-sm shadow-sm group-hover:shadow-md transition-shadow">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </div>
                        Liked Songs
                    </a>
                    <a href="search.php" class="flex items-center gap-4 text-slate-300 hover:text-white transition-colors font-bold <?php echo basename($_SERVER['PHP_SELF']) == 'search.php' ? 'text-white' : ''; ?>">
                        <span class="material-symbols-outlined text-3xl">search</span>
                        Search
                    </a>
                </nav>
            </div>

            <!-- Your Library -->
            <div class="bg-card-dark rounded-lg flex-1 overflow-hidden flex flex-col">
                <div class="p-4 flex items-center justify-between text-slate-300">
                    <button class="flex items-center gap-3 font-bold hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">library_music</span>
                        Your Library
                    </button>
                    <a href="create_playlist.php" class="hover:text-white hover:bg-white/10 p-1 rounded-full transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined">add</span>
                    </a>
                </div>
                
                <!-- Playlist Scrollable Area -->
                <div class="overflow-y-auto px-2 pb-2 flex-1 mt-2">
                    <?php
                    $uid = intval($_SESSION['user_id']);
                    $pl_query = mysqli_query($conn, "SELECT * FROM playlists WHERE user_id = $uid ORDER BY created_at DESC");
                    
                    if(mysqli_num_rows($pl_query) > 0):
                        while($pl = mysqli_fetch_assoc($pl_query)):
                    ?>
                        <a href="playlist.php?id=<?php echo $pl['id']; ?>" class="flex items-center gap-3 p-2 hover:bg-white/10 rounded-md transition-colors cursor-pointer group">
                            <div class="w-12 h-12 bg-[#282828] rounded flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-slate-400 group-hover:text-white">music_note</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-white font-bold truncate"><?php echo htmlspecialchars($pl['name']); ?></span>
                                <span class="text-xs text-slate-400">Playlist • <?php echo $_SESSION['username']; ?></span>
                            </div>
                        </a>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <div class="bg-card-hover/50 p-4 rounded-lg my-2 mx-2">
                            <h4 class="font-bold text-white mb-2 text-sm">Create your first playlist</h4>
                            <p class="text-xs text-slate-300 mb-4 font-semibold">It's easy, we'll help you</p>
                            <a href="create_playlist.php" class="inline-block bg-white text-black font-bold text-sm px-4 py-2 rounded-full hover:scale-105 transition-transform">
                                Create playlist
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 bg-card-dark md:rounded-lg md:my-2 md:mr-2 flex flex-col overflow-hidden relative w-full">
            
            <!-- Topbar (Sticky) -->
            <header class="h-16 flex items-center justify-between px-6 bg-card-dark/95 backdrop-blur-sm sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-slate-300 hover:text-white" onclick="history.back()">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-slate-300 hover:text-white" onclick="history.forward()">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/index.php" class="text-sm font-bold text-slate-300 hover:text-white transition-colors">Admin Panel</a>
                    <?php endif; ?>
                    
                    <div class="relative group cursor-pointer">
                        <button class="flex items-center gap-2 bg-black/50 hover:bg-card-hover p-1 pr-3 rounded-full transition-colors">
                            <div class="w-7 h-7 bg-slate-600 rounded-full flex items-center justify-center text-sm font-bold">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                            <span class="text-sm font-bold whitespace-nowrap"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="material-symbols-outlined text-lg">arrow_drop_down</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-56 bg-[#282828] rounded-md shadow-xl py-2 hidden group-hover:block z-50">
                            <!-- Profile Header details -->
                            <div class="px-4 py-3 border-b border-white/10 mb-1 flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-600 rounded-full flex items-center justify-center text-lg font-bold text-white shrink-0">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <div class="flex flex-col overflow-hidden">
                                     <span class="text-white font-bold text-sm truncate"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                     <span class="text-slate-400 text-xs truncate capitalize"><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'User'; ?></span>
                                </div>
                            </div>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-slate-300 hover:bg-white/10 hover:text-white">Log out</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <div class="flex-1 overflow-y-auto w-full">
                <!-- Inner content wrapper for padding -->
                <div class="p-4 md:p-6">
