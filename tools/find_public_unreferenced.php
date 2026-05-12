<?php
$root = realpath(__DIR__ . '/..');
$public = $root . '/public';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($public));
$publicFiles = [];
foreach ($rii as $f) {
    if (!$f->isFile()) continue;
    $path = $f->getPathname();
    $publicFiles[$path] = basename($path);
}

function findRefs($root, $basename, $excludePath=null) {
    $matches = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    foreach ($it as $o) {
        if (!$o->isFile()) continue;
        $op = $o->getPathname();
        if ($op === $excludePath) continue;
        $ext = strtolower(pathinfo($op, PATHINFO_EXTENSION));
        // skip large binaries
        if (in_array($ext, ['jpg','jpeg','png','gif','jfif','mp4','mp3','ico'])) {
            // still check text-based references in CSS/HTML/JS/PHP
        }
        $contents = @file_get_contents($op);
        if ($contents === false) continue;
        if (strpos($contents, $basename) !== false) $matches[] = $op;
    }
    return $matches;
}

$report = [];
foreach ($publicFiles as $path => $basename) {
    // skip index and asset manifest that are structural
    if (in_array($basename, ['index.php', '.htaccess'])) continue;

    $refs = findRefs($root, $basename, $path);
    $report[] = [
        'path' => $path,
        'basename' => $basename,
        'refs' => $refs,
        'count' => count($refs),
    ];
}

// sort by ascending count
usort($report, function($a,$b){ return $a['count'] <=> $b['count']; });

foreach ($report as $r) {
    echo $r['basename'] . " -> referenced in " . $r['count'] . " files\n";
    if ($r['count'] > 0) {
        foreach ($r['refs'] as $ref) echo "  - $ref\n";
    }
    echo "\n";
}

echo "Done.\n";
