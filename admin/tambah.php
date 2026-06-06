<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $lyrics = mysqli_real_escape_string($conn, $_POST['lyrics']);
    
    // File upload paths
    $cover_name = "";
    $audio_name = "";
    $video_name = "";

    // Handle Cover Image
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $cover_name = time() . '_' . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], "../uploads/covers/" . $cover_name);
    }

    // Handle Audio File
    if(isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
        $audio_name = time() . '_' . $_FILES['audio']['name'];
        move_uploaded_file($_FILES['audio']['tmp_name'], "../uploads/audio/" . $audio_name);
    }

    // Handle Video File
    if(isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video_name = time() . '_' . $_FILES['video']['name'];
        move_uploaded_file($_FILES['video']['tmp_name'], "../uploads/video/" . $video_name);
    }

    if(empty($title) || empty($artist)) {
        $error = "Title and Artist are required!";
    } else {
        $query = "INSERT INTO songs (title, artist, genre, cover_image, file_audio, file_video, lyrics) 
                  VALUES ('$title', '$artist', '$genre', '$cover_name', '$audio_name', '$video_name', '$lyrics')";
                  
        if(mysqli_query($conn, $query)) {
            $_SESSION['success_msg'] = "Track added successfully!";
            header("Location: index.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <title>Add Track - Admin - GMusic</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📻</text></svg>">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1db954", "background-dark": "#121212", "card-dark": "#181818"
                    },
                    fontFamily: {"display": ["Public Sans"]},
                },
            },
        }
    </script>
</head>
<body class="bg-background-dark text-slate-100 font-display min-h-screen p-8">
    <div class="max-w-2xl mx-auto">
        <a href="index.php" class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span> Back to Dashboard
        </a>

        <div class="bg-card-dark p-8 rounded-xl shadow-2xl border border-white/5">
            <h2 class="text-3xl font-bold mb-6">Add New Track</h2>

            <?php if(!empty($error)): ?>
                <div class="bg-red-500/20 text-red-400 p-3 rounded mb-6 text-sm border border-red-500/50"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="tambah.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-300">Artist <span class="text-red-500">*</span></label>
                        <input type="text" name="artist" required class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-300">Genre</label>
                    <select name="genre" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white" required>
                        <option value="Pop">Pop</option>
                        <option value="Rock">Rock</option>
                        <option value="Hip-Hop">Hip-Hop</option>
                        <option value="Jazz">Jazz</option>
                        <option value="Electronic">Electronic</option>
                        <option value="K-Pop">K-Pop</option>
                        <option value="Classical">Classical</option>
                        <option value="R&B">R&B</option>
                        <option value="Country">Country</option>
                        <option value="Folk">Folk</option>
                        <option value="Metal">Metal</option>
                        <option value="Blues">Blues</option>
                        <option value="Reggae">Reggae</option>
                        <option value="Dance">Dance</option>
                        <option value="Indie">Indie</option>
                        <option value="Alternative">Alternative</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-300">Lyrics</label>
                    <textarea name="lyrics" rows="5" placeholder="Enter lyrics here..." class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white"></textarea>
                </div>

                <div class="space-y-4 p-4 border border-white/10 rounded bg-[#2a2a2a]/50">
                    <h3 class="font-bold text-white border-b border-white/10 pb-2">Media Files</h3>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Cover Image (.jpg, .png)</label>
                        <input type="file" name="cover" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-black file:font-bold hover:file:bg-primary/90 transition-colors cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Audio File (.mp3)</label>
                        <input type="file" name="audio" accept="audio/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-black file:font-bold hover:file:bg-slate-200 transition-colors cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Video File (.mp4) <span class="text-slate-500 font-normal italic">- Optional</span></label>
                        <input type="file" name="video" accept="video/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-black file:font-bold hover:file:bg-slate-200 transition-colors cursor-pointer">
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="bg-primary hover:scale-105 text-black font-bold py-3 px-8 rounded-full transition-transform">Save Track</button>
                    <a href="index.php" class="bg-transparent border border-slate-500 text-slate-300 hover:text-white hover:border-white font-bold py-3 px-8 rounded-full transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
