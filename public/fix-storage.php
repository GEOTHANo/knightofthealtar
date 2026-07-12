<?php
/**
 * TEMPORARY — Storage permissions fixer & diagnostics.
 * DELETE after use! Access via /fix-storage.php
 */

echo '<pre style="font-family:monospace;background:#111;color:#0f0;padding:20px;">';
echo "=== Server Diagnostics ===\n\n";

// PHP version
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n\n";

// Paths
$root   = dirname(__DIR__);
$storage = $root . '/storage';
$cache   = $root . '/bootstrap/cache';

echo "App Root: $root\n";
echo "Storage: $storage\n";
echo "Bootstrap Cache: $cache\n\n";

// Check writable
$dirs = [
    $storage . '/app',
    $storage . '/app/public',
    $storage . '/framework',
    $storage . '/framework/cache',
    $storage . '/framework/sessions',
    $storage . '/framework/views',
    $storage . '/logs',
    $cache,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "📁 Created: $dir\n";
    }
    if (is_writable($dir)) {
        echo "✅ Writable: $dir\n";
    } else {
        echo "❌ NOT writable: $dir\n";
        @chmod($dir, 0777);
        echo "   → Attempted chmod 0777\n";
    }
}

// Check .env
$envFile = $root . '/.env';
echo "\n.env exists: " . (file_exists($envFile) ? "YES" : "NO") . "\n";
echo ".env readable: " . (is_readable($envFile) ? "YES" : "NO") . "\n";

// Check vendor autoload
$autoload = $root . '/vendor/autoload.php';
echo "\nvendor/autoload.php exists: " . (file_exists($autoload) ? "YES" : "NO") . "\n";

// Try loading Laravel
echo "\n--- Attempting to boot Laravel ---\n";
try {
    require $autoload;
    $app = require $root . '/bootstrap/app.php';
    echo "✅ Laravel booted successfully!\n";
} catch (Throwable $e) {
    echo "❌ Laravel boot error:\n";
    echo $e->getMessage() . "\n";
    echo "In: " . $e->getFile() . " line " . $e->getLine() . "\n";
}

echo "\n⚠️ DELETE THIS FILE AFTER USE!\n";
echo '</pre>';
