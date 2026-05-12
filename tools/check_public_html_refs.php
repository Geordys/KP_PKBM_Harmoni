<?php
$root = realpath(__DIR__ . '/..');
$public = $root . '/public';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($public));
$files = [];
foreach ($rii as $f) {
    if (!$f->isFile()) continue;
    $path = $f->getPathname();
    if (pathinfo($path, PATHINFO_EXTENSION) !== 'html') continue;
    $files[] = $path;
}

// search in repo for references to each basename
foreach ($files as $file) {
    $basename = basename($file);
    $count = 0;
    $it2 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    foreach ($it2 as $other) {
        if (!$other->isFile()) continue;
        $op = $other->getPathname();
        if ($op === $file) continue;
        $contents = @file_get_contents($op);
        if ($contents === false) continue;
        if (strpos($contents, $basename) !== false) $count++;
    }
    echo $basename . " -> referenced in " . $count . " files\n";
}
