<?php

namespace renders\plain;

function prepareValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_null($value)) {
        return 'null';
    }

    return $value;
}

function renderIter($tree, &$buffer, $path = '')
{
    foreach ($tree as $node) {
        $key = $path ? $path . '.' . $node['name'] : $node['name'];
        switch ($node['type']) {
            case 'deleted':
                $buffer[] = "Property '" . $key . "' was removed";
                break;
            case 'added':
                $buffer[] = "Property '" . $key . "' was added with value: " . prepareValue($node['newValue']);
                break;
            case 'unchanged':
                break;
            case 'changed':
                $buffer[] = "Property '" . $key . "' was updated. From " . prepareValue($node['oldValue']) . " to " . prepareValue($node['newValue']);
                break;
            case 'nested':
                renderIter($node['children'], $buffer, $key);
                break;

            default:
                \utils\unreachable();
                break;
        }
    }
}

function render($diff)
{
    $buffer = [];
    renderIter($diff, $buffer);

    return implode("\n", $buffer);
}
