<?php

namespace parsers;

use Symfony\Component\Yaml\Yaml;

function parseYml(string $rawData): array
{
    return Yaml::parse($rawData);
}

function parseJson(string $rawData): array
{
    return json_decode($rawData, true);
}

function parse(string $rawData, string $extension): array
{
    switch ($extension) {
        case 'yml':
        case 'yaml':
            return parseYml($rawData);
            break;
        case 'json':
            return parseJson($rawData);
            break;

        default:
            \utils\unreachable();
            break;
    }
}
