<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$src = $root . DIRECTORY_SEPARATOR . 'openapi.yaml';
$dst = $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'openapi.yaml';

if (! is_file($src)) {
    exit(0);
}

@mkdir(dirname($dst), 0755, true);
copy($src, $dst);
