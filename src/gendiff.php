<?php

namespace gendiff;

function prepareValue($value)
{
    if ($value === false) {
        return 'false';
    }

    if ($value === true) {
        return 'true';
    }

    return $value;
}

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
            $type = $oldData[$key] === $newData[$key] ? 'unchanged' : 'changed';
        } else {
            \utils\unreachable();
        }

        $acc[$key] = [
            'type' => $type,
            'name' => $key,
            'oldValue' => isset($oldData[$key]) ? prepareValue($oldData[$key]) : null,
            'newValue' => isset($newData[$key]) ? prepareValue($newData[$key]) : null,
        ];

        return $acc;
    }, []);
}

function renderDiff($diff)
{
    $result = array_reduce($diff, function ($acc, $data) {
        switch ($data['type']) {
            case 'deleted':
                $acc[] = "  - {$data['name']}: {$data['oldValue']}";
                break;
            case 'added':
                $acc[] = "  + {$data['name']}: {$data['newValue']}";
                break;
            case 'unchanged':
                $acc[] = "    {$data['name']}: {$data['oldValue']}";
                break;
            case 'changed':
                $acc[] = "  - {$data['name']}: {$data['oldValue']}";
                $acc[] = "  + {$data['name']}: {$data['newValue']}";
                break;

            default:
                \utils\unreachable();
                break;
        }

        return $acc;
    }, ['{']);

    $result[] = '}';

    return implode("\n", $result);
}

function getExtension($filepath)
{
    $parts = explode('.', $filepath);
    return $parts[count($parts) - 1];
}

function gendiff(string $oldFilepath, string $newFilepath): string
{
    $oldContents = file_get_contents($oldFilepath);
    $newContents = file_get_contents($newFilepath);
    $extension = getExtension($oldFilepath);
    $oldParsedData = \parsers\parse($oldContents, $extension);
    $newParsedData = \parsers\parse($newContents, $extension);

    return renderDiff(genDiffStruct($oldParsedData, $newParsedData));
}
