<?php
// Simple debug script to check upload logs
// Access via: https://yanjirestaurant.com/booking/check-upload-log.php

header('Content-Type: text/plain; charset=utf-8');

$error_log = __DIR__ . '/error.log';

if (!file_exists($error_log)) {
    echo "No error log file found at: " . $error_log . "\n";
    exit;
}

echo "=== Upload Debug Log ===\n";
echo "File: " . $error_log . "\n\n";

// Show last 50 lines
$lines = file($error_log);
$relevant_lines = array_filter($lines, function($line) {
    return strpos($line, 'UPLOAD-DEBUG') !== false || strpos($line, 'upload') !== false;
});

$last_lines = array_slice($relevant_lines, -50);

foreach ($last_lines as $line) {
    echo $line;
}

echo "\n\n=== Full Last 100 Lines ===\n";
$all_last_lines = array_slice($lines, -100);
foreach ($all_last_lines as $line) {
    echo $line;
}
?>
