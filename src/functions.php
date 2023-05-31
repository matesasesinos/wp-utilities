<?php

if (!function_exists('mtIsAssociativeArray')) {
    function mtIsAssociativeArray($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}

if (!function_exists('mtArrayAssocToString')) {
    function mtArrayAssocToString($array)
    {
        $string = implode(', ', array_map(function ($k, $v) {
            return "`{$k}`" . ' ' . $v;
        }, array_keys($array), $array));

        return $string;
    }
}
