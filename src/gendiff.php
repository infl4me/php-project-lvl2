<?php

namespace gendiff;

const MAX_FILE_LENGTH = 1024 * 1024;

function unreachable()
{
    print_r("TRIED TO ACCESS UNREACHABLE CODE\n");
    exit(-1);
}

function prepareJsonValue($value)
{
    if ($value === false) {
        return 'false';
    }

    if ($value === true) {
        return 'true';
    }

    return $value;
}

function genJsonDiff($oldRawJson, $newRawJson)
{
    $oldJson = json_decode($oldRawJson, true);
    $newJson = json_decode($newRawJson, true);

    $union = (array_unique(array_merge(array_keys($oldJson), array_keys($newJson))));

    return array_reduce($union, function ($acc, $key) use ($oldJson, $newJson) {
        $type = null;
        if (isset($oldJson[$key]) && !isset($newJson[$key])) {
            $type = 'deleted';
        } else if (!isset($oldJson[$key]) && isset($newJson[$key])) {
            $type = 'added';
        } else if (isset($oldJson[$key]) && isset($newJson[$key])) {
            $type = $oldJson[$key] === $newJson[$key] ? 'unchanged' : 'changed';
        } else {
            unreachable();
        }

        $acc[$key] = [
            'type' => $type,
            'name' => $key,
            'oldValue' => isset($oldJson[$key]) ? prepareJsonValue($oldJson[$key]) : null,
            'newValue' => isset($newJson[$key]) ? prepareJsonValue($newJson[$key]) : null,
        ];

        return $acc;
    }, []);
}

function renderDiff($diff)
{
    $result = array_reduce($diff, function ($acc, $data) use ($diff) {
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
                unreachable();
                break;
        }

        return $acc;
    }, ['{']);

    $result[] = '}';

    return implode("\n", $result);
}

function gendiff($oldFilepath, $newFilepath)
{
    $oldContents = file_get_contents($oldFilepath);
    $newContents = file_get_contents($newFilepath);

    return renderDiff(genJsonDiff($oldContents, $newContents));
}
