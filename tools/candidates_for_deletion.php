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
        $contents = @file_get_contents($op);
        if ($contents === false) continue;
        if (strpos($contents, $basename) !== false) $matches[] = $op;
    }
    return $matches;
}

$candidates = [];
foreach ($publicFiles as $path => $basename) {
    if (in_array($basename, ['index.php', '.htaccess'])) continue;
    $refs = findRefs($root, $basename, $path);
    if (count($refs) === 0) {
        $candidates[] = ['path'=>$path,'basename'=>$basename,'reason'=>'no refs'];
    } elseif (count($refs) === 1 && strpos($refs[0], DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'index') !== false) {
        $candidates[] = ['path'=>$path,'basename'=>$basename,'reason'=>'.git index only'];
    }
}

usort($candidates, fn($a,$b)=>strcmp($a['basename'],$b['basename']));
foreach ($candidates as $c) echo $c['basename'] . " (".$c['reason'].") -> " . $c['path'] . "\n";

echo "Found " . count($candidates) . " candidates.\n";
