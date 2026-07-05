<?php

declare(strict_types=1);

if (!function_exists('vltda_asset')) {

    function vltda_asset(string $path): string
    {
        return trailingslashit(get_template_directory_uri()) . 'assets/' . ltrim($path, '/');
    }
}
