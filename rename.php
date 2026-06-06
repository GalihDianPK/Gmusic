<?php
$files = [
    'register.php', 
    'login.php', 
    'header.php', 
    'admin/index.php', 
    'admin/tambah.php', 
    'admin/edit.php', 
    'database.sql'
];
$favicon = '<link rel="icon" href="data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><text y=\'.9em\' font-size=\'90\'>📻</text></svg>">';

foreach($files as $file) {
    $path = __DIR__ . '/' . $file;
    if(file_exists($path)) {
        $content = file_get_contents($path);
        // Rename
        $content = str_replace('MusicStream', 'GMusic', $content);
        // Add Favicon
        if (strpos($content, '<title>') !== false && strpos($content, '<link rel="icon"') === false) {
            $content = preg_replace('/(<title>.*?<\/title>)/i', "$1\n    $favicon", $content);
        }
        file_put_contents($path, $content);
    }
}
echo "Replaced properly!";
?>
