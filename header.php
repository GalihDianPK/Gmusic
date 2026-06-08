<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'koneksi.php';
// Fetch active event setting
$event_query = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'active_event'");
$active_event = 'none';
if ($row = mysqli_fetch_assoc($event_query)) {
    $active_event = $row['setting_value'];
}
if ($active_event === 'batik') {
    $active_event = 'doodle';
}
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
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' fill='%231db954'><path d='M6 6H42L36 24L42 42H6L12 24L6 6Z'></path></svg>">
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
                        "primary-hover": "#1aa34a",
                        "background-dark": "#121212",
                        "card-dark": "#181818",
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
        /* Sleek Dark Theme (Spotify-like) Overrides */
        body {
            background-color: #121212 !important;
            color: #f1f5f9 !important;
        }
        main, aside, #right-sidebar {
            background-color: #181818 !important;
            border-color: #282828 !important;
        }
        #global-player-bar {
            background-color: #121212 !important;
            border-color: #282828 !important;
        }
        .bg-card-dark {
            background-color: #181818 !important;
        }
        .bg-card-hover {
            background-color: #282828 !important;
        }
        
        /* Custom song cards with dark theme & glowing green accents */
        .song-card, .genre-card {
            border-radius: 12px !important;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid rgba(29, 185, 84, 0.08) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        /* Spotify-style Quick Pick Card (Grey semi-transparent blocks) */
        .quick-pick-card {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border-radius: 4px !important;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease !important;
            border: 1px solid rgba(255, 255, 255, 0.02) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }
        .quick-pick-card:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
            transform: scale(1.015) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
        
        /* Main Page Content Soft Gradient Latar Belakang (Spotify-style) */
        .main-content-gradient {
            background: linear-gradient(to bottom, #222222 0%, #121212 340px) !important;
        }
        
        /* Gradient variations for normal state - subtle dark green/black mix */
        .card-grad-1 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.05) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
        .card-grad-2 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.08) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
        .card-grad-3 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.03) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
        .card-grad-4 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.06) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
        .card-grad-5 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.04) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
        .card-grad-6 { background: linear-gradient(135deg, rgba(29, 185, 84, 0.09) 0%, rgba(24, 24, 24, 0.8) 100%) !important; }
 
        /* Hover glows & scaling for song/genre cards */
        .song-card:hover, .genre-card:hover {
            transform: translateY(-5px) scale(1.02) !important;
            border-color: rgba(29, 185, 84, 0.4) !important;
        }
        
        /* Hover glows for cards */
        .card-grad-1:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.15) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.25) !important;
        }
        .card-grad-2:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.18) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.3) !important;
        }
        .card-grad-3:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.12) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.2) !important;
        }
        .card-grad-4:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.16) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.28) !important;
        }
        .card-grad-5:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.14) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.24) !important;
        }
        .card-grad-6:hover { 
            background: linear-gradient(135deg, rgba(29, 185, 84, 0.22) 0%, rgba(40, 40, 40, 0.9) 100%) !important;
            box-shadow: 0 12px 24px -6px rgba(29, 185, 84, 0.35) !important;
        }
        
        /* Card text color enhancements */
        .song-card h3, .quick-pick-card h3, .genre-card h3 {
            color: #ffffff !important;
        }
        .song-card p, .quick-pick-card p {
            color: #a7a7a7 !important;
        }
        
        .hover\:bg-card-hover:hover {
            background-color: #282828 !important;
        }
        
        /* Buttons with bg-primary should have black text for high contrast */
        .bg-primary {
            background-color: #1db954 !important;
            color: #121212 !important;
        }
        .bg-primary .material-symbols-outlined, .bg-primary span {
            color: #121212 !important;
        }
        .bg-primary-hover:hover, .hover\:bg-primary-hover:hover {
            background-color: #1aa34a !important;
        }
        .text-primary, .text-primary span, .text-primary .material-symbols-outlined {
            color: #1db954 !important;
        }
        .hover\:text-white:hover {
            color: #ffffff !important;
        }
        /* Sidebar active link specific */
        aside nav a.text-white {
            color: #1db954 !important;
        }
        /* Progress sliders */
        #progress-container, #volume-container {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        #progress-bar, #volume-bar {
            background-color: #1db954 !important;
        }
        /* Form Inputs */
        input[type="text"], input[type="email"], input[type="password"] {
            background-color: #282828 !important;
            color: #ffffff !important;
            border: 1px solid #3e3e3e !important;
        }
        /* Custom Scrollbar for Sleek Dark look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #282828;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1db954;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        /* Liked songs gradient container */
        .from-indigo-500 {
            background: linear-gradient(135deg, #1db954 0%, #121212 100%) !important;
        }
        /* Topbar button contrast fixes */
        header button.bg-black\/50 {
            background-color: rgba(0, 0, 0, 0.7) !important;
            color: #ffffff !important;
        }
        header button.bg-black\/50:hover {
            background-color: rgba(0, 0, 0, 0.9) !important;
        }
        header button.bg-black\/50 span {
            color: #ffffff !important;
        }
        header .bg-slate-600 {
            background-color: #282828 !important;
            color: #ffffff !important;
        }
        /* Mobile bottom navigation bar theme overrides */
        nav.md\:hidden {
            background-color: #121212 !important;
            border-top: 1px solid #282828 !important;
        }
        nav.md\:hidden a {
            color: #a7a7a7 !important;
        }
        nav.md\:hidden a span {
            color: #a7a7a7 !important;
        }
        /* Event Theme Overlays */
        .event-theme-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 180px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.8;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
        }
        
        .event-theme-doodle {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="240" height="160" viewBox="0 0 240 160"><g fill="none" stroke="%231db954" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity="0.09"><path d="M20,25 h30 v20 h-30 z M27,31 h16 v8 h-16 z M32,35 a2,2 0 1,0 4,0 a2,2 0 1,0 -4,0" /><path d="M75,35 A3,2.5 0 1,1 72,32.5 L72,18 L85,22 L85,28 A3,2.5 0 1,1 82,25.5" /><path d="M195,45 A3,2.5 0 1,1 192,42.5 L192,28 L205,32 L205,38 A3,2.5 0 1,1 202,35.5" /><path d="M35,125 A3,2.5 0 1,1 32,122.5 L32,108 L45,112 L45,118 A3,2.5 0 1,1 42,115.5" /><circle cx="145" cy="115" r="15" /><circle cx="145" cy="115" r="5" /><path d="M100,60 C90,60 85,70 95,80 C90,90 105,100 110,85 C115,75 110,60 100,60 Z" /><path d="M105,75 L125,55" stroke-width="1.5" /><circle cx="180" cy="140" r="1.5" /><circle cx="15" cy="75" r="2" /><circle cx="110" cy="20" r="1" /></g><g fill="none" stroke="%23ffffff" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity="0.07"><rect x="210" y="90" width="8" height="18" rx="4" /><path d="M214,108 L214,118 M210,118 H218" /><path d="M130,35 C130,22 150,22 150,35 M127,32 H133 V38 H127 Z M147,32 H153 V38 H147 Z" /><path d="M100,25 L102,30 L107,31 L103,35 L104,40 L100,37 Z" /><path d="M175,25 L177,30 L182,31 L178,35 L179,40 L175,37 Z" /><path d="M75,120 L77,125 L82,126 L78,130 L79,135 L75,132 Z" stroke-width="0.8" /><circle cx="205" cy="125" r="12" /><path d="M201,128 Q205,132 209,128" /><circle cx="201" cy="123" r="1" fill="%23ffffff" /><circle cx="209" cy="123" r="1" fill="%23ffffff" /><path d="M45,75 C45,70 60,70 60,75 C60,80 50,80 47,83 L47,80 C45,80 45,77 45,75 Z" /><path d="M8,130 L18,135 L14,140 L22,148 L12,143 L16,138 Z" /><path d="M225,25 L225,17 M221,21 H229" stroke-dasharray="2 2" /><path d="M50,15 L50,8 M46,12 H54" stroke-dasharray="2 2" /></g></svg>');
            background-repeat: repeat;
        }
        
        .event-theme-ramadhan {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45"><path d="M22.5 0 L27.5 17.5 L45 22.5 L27.5 27.5 L22.5 45 L17.5 27.5 L0 22.5 L17.5 17.5 Z" fill="none" stroke="%23D97706" stroke-width="0.8" opacity="0.12"/><circle cx="22.5" cy="22.5" r="4.5" fill="none" stroke="%23D97706" stroke-width="1.2" opacity="0.18"/></svg>');
            background-repeat: repeat;
        }
        
        .event-theme-lebaran {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 55 55"><rect x="17.5" y="17.5" width="20" height="20" transform="rotate(45 27.5 27.5)" fill="none" stroke="%2316A34A" stroke-width="1.2" opacity="0.15"/><path d="M27.5 5 L27.5 50 M5 27.5 L50 27.5" stroke="%2316A34A" stroke-width="0.8" opacity="0.12"/></svg>');
            background-repeat: repeat;
        }
        
        .event-theme-christmas {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50"><circle cx="12" cy="12" r="2" fill="%23DC2626" opacity="0.15"/><circle cx="38" cy="25" r="3" fill="%2316A34A" opacity="0.12"/><circle cx="25" cy="38" r="1.5" fill="%23DC2626" opacity="0.15"/></svg>');
            background-repeat: repeat;
        }
        
        
        /* Event Animations styling */
        
        /* Snowflakes */
        .snowflake {
            position: absolute;
            color: #ffffff;
            font-size: 1.2rem;
            opacity: 0;
            top: -20px;
            animation: fall 8s linear infinite;
            pointer-events: none;
            z-index: 2;
            will-change: transform, top, opacity;
            transform: translate3d(0,0,0);
            backface-visibility: hidden;
        }
        .sf-1 { left: 10%; animation-delay: 0s; animation-duration: 7s; }
        .sf-2 { left: 25%; animation-delay: 2s; animation-duration: 9s; font-size: 1.5rem; opacity: 0.7; }
        .sf-3 { left: 40%; animation-delay: 4s; animation-duration: 8s; }
        .sf-4 { left: 55%; animation-delay: 1s; animation-duration: 10s; font-size: 1.8rem; opacity: 0.6; }
        .sf-5 { left: 70%; animation-delay: 5s; animation-duration: 7s; }
        .sf-6 { left: 85%; animation-delay: 3s; animation-duration: 11s; }
        .sf-7 { left: 90%; animation-delay: 6s; animation-duration: 8s; }
        .sf-8 { left: 15%; animation-delay: 3.5s; animation-duration: 9.5s; }
 
        @keyframes fall {
            0% {
                top: -20px;
                transform: translate3d(0,0,0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.8;
            }
            90% {
                opacity: 0.8;
            }
            100% {
                top: 180px;
                transform: translate3d(25px,0,0) rotate(360deg);
                opacity: 0;
            }
        }
 
        /* Twinkling Stars */
        .star {
            position: absolute;
            color: #FBBF24;
            font-size: 1rem;
            opacity: 0;
            animation: twinkle 4s ease-in-out infinite;
            pointer-events: none;
            z-index: 2;
            will-change: transform, opacity;
            transform: translate3d(0,0,0);
            backface-visibility: hidden;
        }
        .st-1 { top: 20px; left: 15%; animation-delay: 0s; }
        .st-2 { top: 60px; left: 35%; animation-delay: 1.5s; font-size: 1.4rem; }
        .st-3 { top: 40px; left: 60%; animation-delay: 0.5s; }
        .st-4 { top: 80px; left: 75%; animation-delay: 2.2s; font-size: 0.8rem; }
        .st-5 { top: 30px; left: 85%; animation-delay: 1s; }
        .st-6 { top: 90px; left: 20%; animation-delay: 3s; }
 
        @keyframes twinkle {
            0%, 100% { opacity: 0; transform: translate3d(0,0,0) scale(0.6); }
            50% { opacity: 0.7; transform: translate3d(0,0,0) scale(1.1); }
        }
 
        /* Bats */
        .bat {
            position: absolute;
            font-size: 1.5rem;
            opacity: 0;
            animation: float-bat 12s linear infinite;
            pointer-events: none;
            z-index: 2;
            will-change: transform, left, opacity;
            transform: translate3d(0,0,0);
            backface-visibility: hidden;
        }
        .bt-1 { top: 120px; left: -30px; animation-delay: 0s; animation-duration: 12s; }
        .bt-2 { top: 40px; left: -30px; animation-delay: 4s; animation-duration: 14s; }
        .bt-3 { top: 90px; left: -30px; animation-delay: 8s; animation-duration: 13s; }
        .bt-4 { top: 20px; left: -30px; animation-delay: 2s; animation-duration: 15s; }
 
        @keyframes float-bat {
            0% {
                left: -30px;
                transform: translate3d(0,0,0) scale(0.8);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                left: 100%;
                transform: translate3d(0,-20px,0) scale(1.1);
                opacity: 0;
            }
        }
 
        /* Floating Doodle Art Elements */
        .doodle-item {
            position: absolute;
            pointer-events: none;
            z-index: 1;
            animation: float-doodle 12s ease-in-out infinite;
            will-change: transform;
            transform: translate3d(0,0,0);
            backface-visibility: hidden;
        }
        .di-1 {
            top: 15px;
            left: 12%;
            width: 80px;
            height: 80px;
        }
        .di-2 {
            top: 35px;
            left: 68%;
            width: 90px;
            height: 90px;
            animation-delay: -3s;
            animation-duration: 15s;
        }
 
        @keyframes float-doodle {
            0%, 100% { transform: translate3d(0,0,0) rotate(0deg) scale(1); }
            50% { transform: translate3d(0,-15px,0) rotate(8deg) scale(1.08); }
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
                    <a href="search.php" class="flex items-center gap-4 text-slate-300 hover:text-white transition-colors font-bold <?php echo basename($_SERVER['PHP_SELF']) == 'search.php' ? 'text-white' : ''; ?>">
                        <span class="material-symbols-outlined text-3xl">search</span>
                        Search
                    </a>
                    <a href="liked.php" class="flex items-center gap-4 text-slate-300 hover:text-white transition-colors font-bold group">
                        <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white flex items-center justify-center rounded-sm shadow-sm group-hover:shadow-md transition-shadow">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </div>
                        Liked Songs
                    </a>
                </nav>
            </div>
 
            <!-- Your Library -->
            <div class="bg-card-dark rounded-lg flex-1 overflow-hidden flex flex-col relative">
                
                <!-- Background Theme Motif Overlay for Library (Menghias ruang kosong di sidebar dengan rapi & berani) -->
                <?php if ($active_event !== 'none'): ?>
                    <div class="absolute inset-0 pointer-events-none opacity-45 event-theme-<?php echo $active_event; ?> z-0" style="background-size: 80px; background-repeat: repeat; mask-image: linear-gradient(to top, rgba(0,0,0,0.95) 50%, rgba(0,0,0,0) 100%); -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,0.95) 50%, rgba(0,0,0,0) 100%);"></div>
                <?php endif; ?>

                <div class="p-4 flex items-center justify-between text-slate-300 relative z-10">
                    <button class="flex items-center gap-3 font-bold hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">library_music</span>
                        Your Library
                    </button>
                    <a href="create_playlist.php" class="hover:text-white hover:bg-white/10 p-1 rounded-full transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined">add</span>
                    </a>
                </div>
                
                <!-- Playlist Scrollable Area -->
                <div class="overflow-y-auto px-2 pb-2 flex-1 mt-2 relative z-10">
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
 
        <main class="flex-1 bg-card-dark md:rounded-lg md:my-2 md:mr-2 flex flex-col overflow-hidden relative w-full">
            
            <!-- Topbar (Sticky) -->
            <header class="h-16 flex items-center justify-between px-6 bg-card-dark sticky top-0 z-10 border-b border-white/5 shadow-md">
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-slate-300 hover:text-white" onclick="history.back()">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-slate-300 hover:text-white" onclick="history.forward()">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                    
                    <!-- Google-style Event Doodle next to navigation -->
                    <?php if ($active_event !== 'none'): ?>
                        <div class="ml-4 flex items-center gap-2 px-3 py-1 bg-white/15 rounded-full text-sm font-semibold select-none border border-white/10 shadow-sm animate-pulse">
                            <?php
                                switch($active_event) {
                                    case 'doodle':
                                        echo '🎨 <span class="text-xs text-primary font-bold uppercase tracking-wider hidden sm:inline">Doodle Art</span>';
                                        break;
                                    case 'ramadhan':
                                        echo '🌙 <span class="text-xs text-amber-600 font-bold uppercase tracking-wider hidden sm:inline">Ramadhan</span>';
                                        break;
                                    case 'lebaran':
                                        echo '🕌 <span class="text-xs text-green-600 font-bold uppercase tracking-wider hidden sm:inline">Lebaran</span>';
                                        break;
                                    case 'christmas':
                                        echo '🎄 <span class="text-xs text-red-600 font-bold uppercase tracking-wider hidden sm:inline">Christmas</span>';
                                        break;
                                    case 'halloween':
                                        echo '🎃 <span class="text-xs text-orange-600 font-bold uppercase tracking-wider hidden sm:inline">Halloween</span>';
                                        break;
                                }
                            ?>
                        </div>
                    <?php endif; ?>
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
 
            <!-- Event Theme Overlay & Animations (Statis di latar belakang bagian atas, tidak ikut ter-scroll) -->
            <?php if ($active_event !== 'none'): ?>
                <div class="event-theme-overlay event-theme-<?php echo $active_event; ?> absolute left-0 right-0 z-0 pointer-events-none" style="top: 64px;">
                    <?php if ($active_event === 'christmas'): ?>
                        <!-- Snowflakes -->
                        <div class="snowflake sf-1">❄</div>
                        <div class="snowflake sf-2">❄</div>
                        <div class="snowflake sf-3">❅</div>
                        <div class="snowflake sf-4">❆</div>
                        <div class="snowflake sf-5">❄</div>
                        <div class="snowflake sf-6">❅</div>
                        <div class="snowflake sf-7">❆</div>
                        <div class="snowflake sf-8">❄</div>
                    <?php elseif ($active_event === 'ramadhan' || $active_event === 'lebaran'): ?>
                        <!-- Twinkling Stars -->
                        <div class="star st-1">✦</div>
                        <div class="star st-2">✧</div>
                        <div class="star st-3">✦</div>
                        <div class="star st-4">✧</div>
                        <div class="star st-5">✦</div>
                        <div class="star st-6">✦</div>
                    <?php elseif ($active_event === 'halloween'): ?>
                        <!-- Floating bats / ghosts -->
                        <div class="bat bt-1">🦇</div>
                        <div class="bat bt-2">🦇</div>
                        <div class="bat bt-3">👻</div>
                        <div class="bat bt-4">🦇</div>
                    <?php elseif ($active_event === 'doodle'): ?>
                        <!-- Floating doodle art elements -->
                        <div class="doodle-item di-1">
                            <svg class="w-full h-full text-[#1db954] opacity-35" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="15" y="25" width="70" height="50" rx="5" />
                                <rect x="30" y="38" width="40" height="24" rx="2" stroke="#ffffff" />
                                <circle cx="42" cy="50" r="5" stroke="#ffffff" />
                                <circle cx="58" cy="50" r="5" stroke="#ffffff" />
                                <path d="M42,50 H58" stroke="#ffffff" />
                                <rect x="25" y="65" width="50" height="5" rx="1" />
                            </svg>
                        </div>
                        <div class="doodle-item di-2">
                            <svg class="w-full h-full text-white opacity-35" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M30,55 C20,55 15,45 25,38 C20,25 35,15 48,22 C58,12 75,20 70,35 C80,38 80,50 70,55 Z" />
                                <circle cx="40" cy="40" r="1.5" fill="currentColor"/>
                                <circle cx="55" cy="40" r="1.5" fill="currentColor"/>
                                <path d="M44,48 Q47.5,52 51,48" />
                                <path d="M20,20 L20,10 M15,15 H25" stroke="#1db954" stroke-width="1.5" />
                                <path d="M80,25 L80,18 M76,21 H84" stroke="#1db954" stroke-width="1.5" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Event Floating Hanging Decoration (Menggantung di kanan atas, tidak ikut ter-scroll) -->
                <div class="absolute right-6 pointer-events-none select-none z-20" style="width: 80px; height: 120px; top: 64px;">
                    <?php
                        switch($active_event) {
                               case 'doodle':
                                // Hanging doodle record & note
                                echo '<svg class="w-full h-full opacity-90 animate-bounce" style="animation-duration: 4.5s;" viewBox="0 0 100 130">
                                    <line x1="50" y1="0" x2="50" y2="40" stroke="#1db954" stroke-width="2" />
                                    <!-- Vinyl Record -->
                                    <g transform="translate(25, 40)" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="25" cy="25" r="24" fill="#181818" stroke="#ffffff" />
                                        <circle cx="25" cy="25" r="18" stroke="#ffffff" stroke-dasharray="4 2" />
                                        <circle cx="25" cy="25" r="12" stroke="#ffffff" />
                                        <circle cx="25" cy="25" r="8" fill="#1db954" stroke="none" />
                                        <circle cx="25" cy="25" r="2" fill="#121212" stroke="none" />
                                    </g>
                                    <!-- Cute Music Note -->
                                    <g transform="translate(52, 72)" fill="none" stroke="#1db954" stroke-width="2">
                                        <path d="M10,25 A3,2 0 1,1 7,23 L7,5 L20,9 L20,18 A3,2 0 1,1 17,16" />
                                        <path d="M7,5 L20,9" stroke="#ffffff" stroke-width="2" />
                                    </g>
                                </svg>';
                                break;
                            case 'ramadhan':
                                // Hanging golden lantern
                                echo '<svg class="w-full h-full text-amber-600 opacity-90 animate-bounce" style="animation-duration: 4s;" viewBox="0 0 100 130">
                                    <line x1="50" y1="0" x2="50" y2="40" stroke="#B45309" stroke-width="2" />
                                    <g transform="translate(30, 40)" fill="#D97706" stroke="#B45309" stroke-width="1.5">
                                        <path d="M20 0 Q20 -15 0 -15 Q-20 -15 -20 0 Z" transform="translate(20, 15)"/>
                                        <rect x="0" y="15" width="40" height="40" rx="4" fill="#FEF3C7" />
                                        <path d="M20 25 Q24 35 20 45 Q16 35 20 25 Z" fill="#F59E0B" stroke="none"/>
                                        <line x1="0" y1="35" x2="40" y2="35" stroke="#B45309" />
                                        <line x1="20" y1="15" x2="20" y2="55" stroke="#B45309" />
                                        <rect x="5" y="55" width="30" height="8" rx="2" fill="#D97706" />
                                        <circle cx="20" cy="68" r="5" fill="none" stroke="#B45309" stroke-width="1.5" />
                                    </g>
                                </svg>';
                                break;
                            case 'lebaran':
                                // Hanging green ketupat
                                echo '<svg class="w-full h-full opacity-90 animate-bounce" style="animation-duration: 5s;" viewBox="0 0 100 140">
                                    <line x1="50" y1="0" x2="50" y2="40" stroke="#15803D" stroke-width="2" />
                                    <g transform="translate(50, 65) rotate(45)" stroke="#166534" stroke-width="1.5">
                                        <rect x="-20" y="-20" width="40" height="40" fill="#22C55E" />
                                        <rect x="-20" y="-20" width="20" height="20" fill="#4ADE80" />
                                        <rect x="0" y="0" width="20" height="20" fill="#4ADE80" />
                                        <rect x="0" y="-20" width="20" height="20" fill="#86EFAC" />
                                        <rect x="-20" y="0" width="20" height="20" fill="#86EFAC" />
                                    </g>
                                    <path d="M43 85 L35 125 M57 85 L65 125" stroke="#4ADE80" stroke-width="4" stroke-linecap="round" opacity="0.9"/>
                                    <path d="M48 85 L45 130 M52 85 L55 130" stroke="#22C55E" stroke-width="3" stroke-linecap="round" opacity="0.9"/>
                                </svg>';
                                break;
                            case 'christmas':
                                // Hanging red Christmas ball
                                echo '<svg class="w-full h-full opacity-95 animate-bounce" style="animation-duration: 3s;" viewBox="0 0 100 130">
                                    <line x1="50" y1="0" x2="50" y2="40" stroke="#B91C1C" stroke-width="2" />
                                    <rect x="42" y="38" width="16" height="8" fill="#F59E0B" rx="1"/>
                                    <circle cx="50" cy="74" r="26" fill="#DC2626" stroke="#991B1B" stroke-width="1.5"/>
                                    <ellipse cx="42" cy="64" rx="8" ry="5" fill="#FECACA" transform="rotate(-30 42 64)"/>
                                    <path d="M38 90 Q50 98 62 90" fill="none" stroke="#FEF08A" stroke-width="2" stroke-dasharray="2 2"/>
                                </svg>';
                                break;
                            case 'halloween':
                                // Hanging pumpkin
                                echo '<svg class="w-full h-full opacity-95 animate-bounce" style="animation-duration: 6s;" viewBox="0 0 100 130">
                                    <line x1="50" y1="0" x2="50" y2="40" stroke="#C2410C" stroke-width="2" />
                                    <g transform="translate(50, 70)">
                                        <path d="M-4 -30 Q0 -38 8 -34" fill="none" stroke="#15803D" stroke-width="4" stroke-linecap="round"/>
                                        <ellipse cx="0" cy="0" rx="28" ry="24" fill="#F97316" stroke="#C2410C" stroke-width="1.5"/>
                                        <ellipse cx="0" cy="0" rx="18" ry="24" fill="#FB923C" stroke="#C2410C" stroke-width="1"/>
                                        <ellipse cx="0" cy="0" rx="8" ry="24" fill="#FFEDD5" opacity="0.1" stroke="#C2410C" stroke-width="1"/>
                                        <polygon points="-12,-6 -4,-6 -8,-14" fill="#7C2D12"/>
                                        <polygon points="4,-6 12,-6 8,-14" fill="#7C2D12"/>
                                        <polygon points="-3,2 3,2 0,-2" fill="#7C2D12"/>
                                        <path d="M-16,6 Q0,16 16,6 Q10,12 -10,12 Z" fill="#7C2D12"/>
                                    </g>
                                </svg>';
                                break;
                        }
                    ?>
                </div>
            <?php endif; ?>
 
            <!-- Scrollable Page Content -->
            <div class="flex-1 overflow-y-auto w-full relative z-10 main-content-gradient">
                <!-- Inner content wrapper for padding -->
                <div class="p-4 md:p-6">
