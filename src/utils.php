<?php

namespace utils;

function unreachable()
{
    throw new \Exception('Tried to access unreachable code.');
}

function extractExtension($filepath)
{
    $parts = explode('.', $filepath);
    return $parts[count($parts) - 1];
}

function debug($value)
{
    print_r("\n-----------------------------\n");
    print_r($value);
    print_r("\n+++++++++++++++++++++++++++++\n");
}
