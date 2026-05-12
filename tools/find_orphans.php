<?php
$root = realpath(__DIR__ . '/..');
$public = $root . '/public';
$excludes = ['vendor', '.git', 'node_modules', 'storage', 'public/assets/images'];
function iterFiles($dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $path = $f->getPathname();
        yield $path;
    }
}

$allFiles = iterator_to_array(iterFiles($root));
$publicFiles = array_filter($allFiles, function($p) use ($public) { return strpos($p, $public) === 0; });
$otherFiles = array_filter($allFiles, function($p) use ($public) { return strpos($p, $public) !== 0; });

$orphans = [];
foreach ($publicFiles as $file) {
    $basename = basename($file);
    // skip some files that are obviously structural
    if (in_array($basename, ['.htaccess', 'index.php'])) continue;
    $found = false;
    foreach ($allFiles as $other) {
        if ($other === $file) continue;
        // skip binary-heavy dirs
        foreach ($excludes as $ex) if (strpos($other, DIRECTORY_SEPARATOR.$ex) !== false) { continue 2; }
        $contents = @file_get_contents($other);
        if ($contents === false) continue;
        if (strpos($contents, $basename) !== false) { $found = true; break; }
    }
    if (!$found) $orphans[] = $file;
}

foreach ($orphans as $o) echo $o . "\n";

echo "Found " . count($orphans) . " potential orphans in public.\n";
