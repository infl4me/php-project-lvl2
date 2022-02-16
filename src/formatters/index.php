<?php

namespace formatters;

function formatDiff(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return \formatters\stylish\format($diff);
        case 'plain':
            return \formatters\plain\format($diff);
        case 'json':
            return \formatters\json\format($diff);

        default:
            \utils\unreachable();
    }
}
