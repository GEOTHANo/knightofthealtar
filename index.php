<?php
// Root index.php fallback — redirects to the Laravel public entry point
// This is needed when IIS URL Rewrite does not fully handle the root request.

chdir(__DIR__ . '/public');
require __DIR__ . '/public/index.php';
