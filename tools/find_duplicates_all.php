<?php
$root = realpath(__DIR__ . '/..');
$excludeDirs = ['.git', 'vendor', 'node_modules'];
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$hashes = [];
foreach ($rii as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    // ignore exclusions
    foreach ($excludeDirs as $ex) if (strpos($path, DIRECTORY_SEPARATOR.$ex) !== false) continue 2;
    $hash = @md5_file($path);
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
