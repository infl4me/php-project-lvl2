<?php

namespace renders;

function prepareValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
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

function renderJson($name, $data, &$buffer, $depth, $sign)
{
    $buffer[] = genDiffSignString($depth, $sign) . $name . ': {';
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            renderJson($key, $value, $buffer, $depth + 1, ' ');
        } else {
            $buffer[] = str_repeat('    ', $depth + 2) . $key . ': ' . prepareValue($value);
        }
    }
    $buffer[] = genBracketSting('}', $depth + 1);
}

function renderNode($node, &$buffer, $depth, $key, $sign)
{
    if (is_array($node[$key])) {
        renderJson($node['name'], $node[$key], $buffer, $depth, $sign);
    } else {
        $buffer[] = genDiffSignString($depth, $sign) . "{$node['name']}: " . prepareValue($node[$key]);
    }
}

function renderIter($tree, &$buffer, $depth)
{
    foreach ($tree as $node) {
        switch ($node['type']) {
            case 'deleted':
                renderNode($node, $buffer, $depth, 'oldValue', '-');
                break;
            case 'added':
                renderNode($node, $buffer, $depth, 'newValue', '+');
                break;
            case 'unchanged':
                renderNode($node, $buffer, $depth, 'oldValue', ' ');
                break;
            case 'changed':
                renderNode($node, $buffer, $depth, 'oldValue', '-');
                renderNode($node, $buffer, $depth, 'newValue', '+');
                break;
            case 'nested':
                $buffer[] = str_repeat('    ', $depth + 1) . $node['name'] . ': {';
                renderIter($node['children'], $buffer, $depth + 1);
                $buffer[] = str_repeat('    ', $depth + 1) . '}';
                break;

            default:
                \utils\unreachable();
                break;
        }
    }
}

function renderDiff($diff)
{
    $buffer = ['{'];
    renderIter($diff, $buffer, 0);
    $buffer[] = '}';

    return implode("\n", $buffer);
}
