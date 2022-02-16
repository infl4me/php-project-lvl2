<?php

namespace gendiff;

function genDiffStruct(array $oldData, array $newData): array
{
    $union = (array_unique(array_merge(array_keys($oldData), array_keys($newData))));

    return array_reduce($union, function ($acc, $key) use ($oldData, $newData) {
        $type = null;
        if (isset($oldData[$key]) && !isset($newData[$key])) {
            $type = 'deleted';
        } elseif (!isset($oldData[$key]) && isset($newData[$key])) {
            $type = 'added';
        } elseif (isset($oldData[$key]) && isset($newData[$key])) {
            if (is_array($oldData[$key]) && is_array($newData[$key])) {
                $type = 'nested';
            } else {
                $type = $oldData[$key] === $newData[$key] ? 'unchanged' : 'changed';
            }
        } else {
            \utils\unreachable();
        }

        $acc[$key] = [
            'type' => $type,
            'name' => $key,
            'oldValue' => isset($oldData[$key]) && $type !== 'nested' ? $oldData[$key] : null,
            'newValue' => isset($newData[$key]) && $type !== 'nested' ? $newData[$key] : null,
            'children' => $type === 'nested' ? genDiffStruct($oldData[$key], $newData[$key]) : null,
        ];

        return $acc;
    }, []);
}

function gendiff(string $oldFilepath, string $newFilepath): string
{
    $oldContents = file_get_contents($oldFilepath);
    $newContents = file_get_contents($newFilepath);
    $extension = \utils\extractExtension($oldFilepath);
    $oldParsedData = \parsers\parse($oldContents, $extension);
    $newParsedData = \parsers\parse($newContents, $extension);

    return \renders\renderDiff(genDiffStruct($oldParsedData, $newParsedData));
}
