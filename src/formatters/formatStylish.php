<?php

namespace formatters\stylish;

function prepareValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return $value;
}

function genBracketSting($bracket, $depth)
{
    return str_repeat('    ', $depth) . $bracket;
}

function genDiffSignString($depth, $sign)
{
    return str_repeat('    ', $depth) . '  ' . $sign . ' ';
}

function formatJson($name, $data, &$buffer, $depth, $sign)
{
    $buffer[] = genDiffSignString($depth, $sign) . $name . ': {';
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            formatJson($key, $value, $buffer, $depth + 1, ' ');
        } else {
            $buffer[] = str_repeat('    ', $depth + 2) . $key . ': ' . prepareValue($value);
        }
    }
    $buffer[] = genBracketSting('}', $depth + 1);
}

function formatNode($node, &$buffer, $depth, $key, $sign)
{
    if (is_array($node[$key])) {
        formatJson($node['name'], $node[$key], $buffer, $depth, $sign);
    } else {
        $buffer[] = genDiffSignString($depth, $sign) . "{$node['name']}: " . prepareValue($node[$key]);
    }
}

function formatIter($tree, &$buffer, $depth)
{
    foreach ($tree as $node) {
        switch ($node['type']) {
            case 'deleted':
                formatNode($node, $buffer, $depth, 'oldValue', '-');
                break;
            case 'added':
                formatNode($node, $buffer, $depth, 'newValue', '+');
                break;
            case 'unchanged':
                formatNode($node, $buffer, $depth, 'oldValue', ' ');
                break;
            case 'changed':
                formatNode($node, $buffer, $depth, 'oldValue', '-');
                formatNode($node, $buffer, $depth, 'newValue', '+');
                break;
            case 'nested':
                $buffer[] = str_repeat('    ', $depth + 1) . $node['name'] . ': {';
                formatIter($node['children'], $buffer, $depth + 1);
                $buffer[] = str_repeat('    ', $depth + 1) . '}';
                break;

            default:
                \utils\unreachable();
                break;
        }
    }
}

function format($diff)
{
    $buffer = ['{'];
    formatIter($diff, $buffer, 0);
    $buffer[] = '}';

    return implode("\n", $buffer);
}
