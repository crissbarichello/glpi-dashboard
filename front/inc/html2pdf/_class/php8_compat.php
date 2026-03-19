<?php

// Compatibility shims for legacy TCPDF 5.x on PHP >= 8.

if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting)
    {
        return false;
    }
}

if (!function_exists('create_function')) {
    function create_function($args, $code)
    {
        return eval('return function(' . $args . ') {' . $code . '};');
    }
}

if (!function_exists('each')) {
    function each(&$array)
    {
        $key = key($array);
        if ($key === null) {
            return false;
        }

        $value = current($array);
        next($array);

        return [
            1       => $value,
            'value' => $value,
            0       => $key,
            'key'   => $key,
        ];
    }
}
