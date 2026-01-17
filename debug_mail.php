<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking jobs table...\n";
try {
    $count = DB::table('jobs')->count();
    echo "Pending jobs: $count\n";
    
    $failedCount = DB::table('failed_jobs')->count();
    echo "Failed jobs: $failedCount\n";
    
    if ($failedCount > 0) {
        $lastFailed = DB::table('failed_jobs')->latest()->first();
        echo "Last failed job error: " . $lastFailed->exception . "\n";
    }
} catch (\Exception $e) {
    echo "Error checking tables: " . $e->getMessage() . "\n";
}

echo "\nChecking mail configuration...\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Encryption: " . config('mail.mailers.smtp.encryption') . "\n";

// Optional: test send if count is 0? 
// No, let's just report status first.
