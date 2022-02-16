<?php

namespace renders;

function renderDiff(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return \renders\stylish\render($diff);
        case 'plain':
            return \renders\plain\render($diff);

        default:
            \utils\unreachable();
    }
}
