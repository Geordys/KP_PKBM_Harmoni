<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "ERROR: vendor/autoload.php not found\n";
    exit(1);
}
require $autoload;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $rows = Illuminate\Support\Facades\DB::table('registrations')->orderBy('id', 'desc')->limit(10)->get()->toArray();
    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
