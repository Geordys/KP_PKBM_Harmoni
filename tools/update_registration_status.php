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

if ($argc < 3) {
    echo "Usage: php update_registration_status.php <nomor_pendaftaran> <status>\n";
    exit(1);
}

$nomor = $argv[1];
$status = strtoupper($argv[2]);

if (!in_array($status, ['MENUNGGU','DIVERIFIKASI','DITERIMA'])) {
    echo "Invalid status. Use MENUNGGU, DIVERIFIKASI, or DITERIMA\n";
    exit(1);
}

try {
    $update = ['status' => $status, 'updated_at' => now()];
    if ($status === 'DIVERIFIKASI' && Illuminate\Support\Facades\Schema::hasColumn('registrations', 'verified_at')) {
        $update['verified_at'] = now();
    }
    if ($status === 'DITERIMA' && Illuminate\Support\Facades\Schema::hasColumn('registrations', 'accepted_at')) {
        $update['accepted_at'] = now();
    }

    $affected = Illuminate\Support\Facades\DB::table('registrations')
        ->where('nomor_pendaftaran', $nomor)
        ->update($update);

    echo "Updated rows: " . (int)$affected . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
