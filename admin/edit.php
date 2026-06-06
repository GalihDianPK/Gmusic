<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM songs WHERE id = $id";
$result = mysqli_query($conn, $query);
$song = mysqli_fetch_assoc($result);

if(!$song) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $lyrics = mysqli_real_escape_string($conn, $_POST['lyrics']);
    
    // Existing values
    $cover_name = $song['cover_image'];
    $audio_name = $song['file_audio'];
    $video_name = $song['file_video'];

    // Handle Cover Image Upload
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $cover_name = time() . '_' . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], "../uploads/covers/" . $cover_name);
        // Delete old cover if exists and not default
        if($song['cover_image'] && file_exists("../uploads/covers/" . $song['cover_image'])) {
            unlink("../uploads/covers/" . $song['cover_image']);
        }
    }

    // Handle Audio Upload
    if(isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
        $audio_name = time() . '_' . $_FILES['audio']['name'];
        move_uploaded_file($_FILES['audio']['tmp_name'], "../uploads/audio/" . $audio_name);
        // Delete old audio if exists
        if($song['file_audio'] && file_exists("../uploads/audio/" . $song['file_audio'])) {
            unlink("../uploads/audio/" . $song['file_audio']);
        }
    }

    // Handle Video Upload
    if(isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video_name = time() . '_' . $_FILES['video']['name'];
        move_uploaded_file($_FILES['video']['tmp_name'], "../uploads/video/" . $video_name);
        // Delete old video if exists
        if($song['file_video'] && file_exists("../uploads/video/" . $song['file_video'])) {
            unlink("../uploads/video/" . $song['file_video']);
        }
    }

    $update_query = "UPDATE songs SET title='$title', artist='$artist', genre='$genre', 
                     cover_image='$cover_name', file_audio='$audio_name', file_video='$video_name', lyrics='$lyrics' 
                     WHERE id=$id";
                  
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['success_msg'] = "Track updated successfully!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <title>Edit Track - Admin - GMusic</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📻</text></svg>">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {"primary": "#1db954", "background-dark": "#121212", "card-dark": "#181818"},
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
            <h2 class="text-3xl font-bold mb-6">Edit Track</h2>

            <?php if(!empty($error)): ?>
                <div class="bg-red-500/20 text-red-400 p-3 rounded mb-6 text-sm border border-red-500/50"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($song['title']); ?>" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-300">Artist <span class="text-red-500">*</span></label>
                        <input type="text" name="artist" required value="<?php echo htmlspecialchars($song['artist']); ?>" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-300">Genre</label>
                    <?php $g = isset($song['genre']) ? $song['genre'] : 'Pop'; ?>
                    <select name="genre" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white" required>
                        <option value="Pop" <?php echo ($g == 'Pop') ? 'selected' : ''; ?>>Pop</option>
                        <option value="Rock" <?php echo ($g == 'Rock') ? 'selected' : ''; ?>>Rock</option>
                        <option value="Hip-Hop" <?php echo ($g == 'Hip-Hop') ? 'selected' : ''; ?>>Hip-Hop</option>
                        <option value="Jazz" <?php echo ($g == 'Jazz') ? 'selected' : ''; ?>>Jazz</option>
                        <option value="Electronic" <?php echo ($g == 'Electronic') ? 'selected' : ''; ?>>Electronic</option>
                        <option value="K-Pop" <?php echo ($g == 'K-Pop') ? 'selected' : ''; ?>>K-Pop</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-300">Lyrics</label>
                    <textarea name="lyrics" rows="5" class="w-full bg-[#2a2a2a] border-transparent focus:border-primary rounded p-3 text-white"><?php echo htmlspecialchars($song['lyrics']); ?></textarea>
                </div>

                <div class="space-y-4 p-4 border border-white/10 rounded bg-[#2a2a2a]/50">
                    <h3 class="font-bold text-white border-b border-white/10 pb-2">Update Media Files <span class="text-xs font-normal text-slate-400 ml-2">(Leave blank to keep current)</span></h3>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Cover Image</label>
                        <?php if($song['cover_image']): ?>
                            <div class="text-xs text-green-400 mb-1">Current: <?php echo htmlspecialchars($song['cover_image']); ?></div>
                        <?php endif; ?>
                        <input type="file" name="cover" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-white file:text-black file:font-semibold cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Audio File</label>
                        <?php if($song['file_audio']): ?>
                            <div class="text-xs text-green-400 mb-1">Current: <?php echo htmlspecialchars($song['file_audio']); ?></div>
                        <?php endif; ?>
                        <input type="file" name="audio" accept="audio/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-white file:text-black file:font-semibold cursor-pointer">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-300">Video File</label>
                        <?php if($song['file_video']): ?>
                            <div class="text-xs text-blue-400 mb-1">Current: <?php echo htmlspecialchars($song['file_video']); ?></div>
                        <?php endif; ?>
                        <input type="file" name="video" accept="video/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-white file:text-black file:font-semibold cursor-pointer">
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="bg-primary hover:scale-105 text-black font-bold py-3 px-8 rounded-full transition-transform">Update Track</button>
                    <a href="index.php" class="bg-transparent border border-slate-500 text-slate-300 hover:text-white hover:border-white font-bold py-3 px-8 rounded-full transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
