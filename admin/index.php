<?php
session_start();
require '../koneksi.php';

// Check if user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all songs
$query = "SELECT * FROM songs ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard - GMusic</title>
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
                        "background-dark": "#121212",
                        "card-dark": "#181818",
                        "card-hover": "#282828"
                    },
                    fontFamily: {"display": ["Public Sans", "sans-serif"]},
                },
            },
        }
    </script>
</head>
<body class="bg-background-dark text-slate-100 font-display min-h-screen">

    <nav class="bg-card-dark border-b border-white/10 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="text-primary">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-white">GMusic <span class="text-primary ml-1 text-sm font-normal">Admin</span></span>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="../index.php" class="text-slate-300 hover:text-white transition-colors text-sm font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-lg">open_in_new</span>
                View Site
            </a>
            <div class="w-px h-6 bg-white/20"></div>
            <a href="../logout.php" class="text-slate-300 hover:text-white transition-colors text-sm font-semibold">Log out</a>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Content Management</h1>
                <p class="text-slate-400 text-sm">Manage all songs, videos, and metadata in the database.</p>
            </div>
            <a href="tambah.php" class="bg-primary hover:bg-opacity-90 text-black font-bold py-2 px-6 rounded-full transition-transform hover:scale-105 flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Add New Content
            </a>
        </div>

        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <?php 
                    echo $_SESSION['success_msg']; 
                    unset($_SESSION['success_msg']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="bg-card-dark rounded-xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-white/5 text-xs uppercase text-slate-400 border-b border-white/5">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Title & Artist</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Genre</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Media Files</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-[#282828] rounded overflow-hidden flex-shrink-0">
                                            <?php $cover = $row['cover_image'] ? $row['cover_image'] : 'default_cover.jpg'; ?>
                                            <img src="../uploads/covers/<?php echo htmlspecialchars($cover); ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-base mb-0.5">
                                                <a href="../player.php?id=<?php echo $row['id']; ?>" class="hover:underline" target="_blank">
                                                    <?php echo htmlspecialchars($row['title']); ?>
                                                </a>
                                            </div>
                                            <div class="text-slate-400 text-sm"><?php echo htmlspecialchars($row['artist']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-white/10 text-slate-300 py-1 px-3 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars($row['genre'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <?php if(!empty($row['file_audio'])): ?>
                                            <span class="material-symbols-outlined text-green-400 text-xl" title="Audio included">headphones</span>
                                        <?php endif; ?>
                                        <?php if(!empty($row['file_video'])): ?>
                                            <span class="material-symbols-outlined text-blue-400 text-xl" title="Video included">movie</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-blue-400 hover:text-blue-300 ml-3 inline-flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-lg">edit</span> Edit
                                    </a>
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="text-red-400 hover:text-red-300 ml-4 inline-flex items-center gap-1 transition-colors" onclick="return confirm('Are you sure you want to delete this track? This action cannot be undone.');">
                                        <span class="material-symbols-outlined text-lg">delete</span> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl mb-2 opacity-50">library_music</span>
                                        <p>No content available. Click "Add New Content" to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
