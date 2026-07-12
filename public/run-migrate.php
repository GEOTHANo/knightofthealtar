<?php
/**
 * TEMPORARY MIGRATION RUNNER
 * DELETE THIS FILE IMMEDIATELY AFTER RUNNING MIGRATIONS.
 * Access via: /run-migrate.php?secret=koa_migrate_now
 */

if (!isset($_GET['secret']) || $_GET['secret'] !== 'koa_migrate_now') {
    http_response_code(403);
    die('403 Forbidden');
}

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<pre style="font-family:monospace;background:#111;color:#0f0;padding:20px;font-size:13px;">';
echo "=== KOA Attendance - Remote Migration Runner ===\n\n";

// Test database connection first
try {
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST') . ';port=' . env('DB_PORT') . ';dbname=' . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "✅ Database connection: SUCCESS\n\n";
} catch (Exception $e) {
    echo "❌ Database connection FAILED: " . $e->getMessage() . "\n";
    echo '</pre>';
    exit;
}

// Run migrations
echo "Running: php artisan migrate --force\n";
echo str_repeat("-", 50) . "\n";

$exitCode = $kernel->call('migrate', ['--force' => true]);

echo $kernel->output();

if ($exitCode === 0) {
    echo "\n✅ Migrations completed successfully!\n";
    echo "\n⚠️  IMPORTANT: DELETE THIS FILE NOW!\n";
    echo "   Remove /public/run-migrate.php from the server.\n";
} else {
    echo "\n❌ Migration failed with exit code: $exitCode\n";
}

echo '</pre>';
