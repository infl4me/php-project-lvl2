<?php

namespace Differ\Differ;

function genDiffStruct(array $oldData, array $newData): array
{
    $union = (array_unique(array_merge(array_keys($oldData), array_keys($newData))));
    sort($union);

    return array_reduce($union, function ($acc, $key) use ($oldData, $newData) {
        $type = null;
        if (array_key_exists($key, $oldData) && !array_key_exists($key, $newData)) {
            $type = 'deleted';
        } elseif (!array_key_exists($key, $oldData) && array_key_exists($key, $newData)) {
            $type = 'added';
        } elseif (array_key_exists($key, $oldData) && array_key_exists($key, $newData)) {
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
            'oldValue' => array_key_exists($key, $oldData) && $type !== 'nested' ? $oldData[$key] : null,
            'newValue' => array_key_exists($key, $newData) && $type !== 'nested' ? $newData[$key] : null,
            'children' => $type === 'nested' ? genDiffStruct($oldData[$key], $newData[$key]) : null,
        ];

        return $acc;
    }, []);
}

function genDiff(string $oldFilepath, string $newFilepath, string $format = 'stylish'): string
{
    $oldContents = file_get_contents($oldFilepath);
    $newContents = file_get_contents($newFilepath);
    $extension = \utils\extractExtension($oldFilepath);
    $oldParsedData = \parsers\parse($oldContents, $extension);
    $newParsedData = \parsers\parse($newContents, $extension);

    return \formatters\formatDiff(genDiffStruct($oldParsedData, $newParsedData), $format);
}
