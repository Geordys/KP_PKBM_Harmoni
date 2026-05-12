<?php
$dir = __DIR__ . '/../public';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$hashes = [];
foreach ($rii as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    // skip .DS_Store or similar
    if (basename($path) === '.DS_Store') continue;
    $hash = md5_file($path);
    if ($hash === false) continue;
    $hashes[$hash][] = $path;
}
foreach ($hashes as $h => $files) {
    if (count($files) > 1) {
        echo "=== DUPLICATE GROUP ===\n";
        foreach ($files as $f) echo $f . "\n";
        echo "\n";
    }
}
echo "Done.\n";
