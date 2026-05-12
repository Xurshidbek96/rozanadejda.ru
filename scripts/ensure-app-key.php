<?php

declare(strict_types=1);

/**
 * Ensures .env exists and APP_KEY is set (fixes MissingAppKeyException after clone or fresh copy).
 * Safe to run multiple times: skips if APP_KEY already has a value.
 */

$root = dirname(__DIR__);
$envPath = $root.DIRECTORY_SEPARATOR.'.env';
$examplePath = $root.DIRECTORY_SEPARATOR.'.env.example';

if (! is_file($envPath)) {
    if (is_file($examplePath)) {
        copy($examplePath, $envPath);
        fwrite(STDOUT, "Created .env from .env.example\n");
    } else {
        fwrite(STDERR, "Missing .env and .env.example. Create .env then run: php artisan key:generate\n");
        exit(1);
    }
}

$env = (string) file_get_contents($envPath);
if (preg_match('/^APP_KEY=(.*)$/m', $env, $m)) {
    $val = trim($m[1], " \t\n\r\0\x0B'\"");
    if ($val !== '') {
        exit(0);
    }
}

fwrite(STDOUT, "APP_KEY is missing or empty — generating application key...\n");

$artisan = $root.DIRECTORY_SEPARATOR.'artisan';
passthru(escapeshellarg(PHP_BINARY).' '.escapeshellarg($artisan).' key:generate --ansi', $code);

exit($code ?? 0);
