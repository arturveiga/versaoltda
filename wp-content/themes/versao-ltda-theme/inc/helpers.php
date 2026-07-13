<?php

declare(strict_types=1);

if (!function_exists('vltda_asset')) {

    function vltda_asset(string $path): string
    {
        return trailingslashit(get_template_directory_uri()) . 'assets/' . ltrim($path, '/');
    }
}

if (!function_exists('versao_ltda_get_wishlist_url')) {

    function versao_ltda_get_wishlist_url(): string
    {
        return home_url('/whitelist/');
    }
}

if (!function_exists('versao_ltda_get_account_address_url')) {

    function versao_ltda_get_account_address_url(): string
    {
        if (function_exists('wc_get_endpoint_url') && function_exists('wc_get_page_permalink')) {
            return wc_get_endpoint_url('edit-address', 'billing', wc_get_page_permalink('myaccount'));
        }

        return home_url('/minha-conta/edit-address/billing/');
    }
}
