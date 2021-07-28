<?php

if (!function_exists('utils')) {
    function utils(): \App\Utils\Utils {
        return app()->get(\App\Utils\Utils::class);
    }
}