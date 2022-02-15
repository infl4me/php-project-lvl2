<?php

namespace gendiff;

const MAX_FILE_LENGTH = 1024 * 1024;

function unreachable()
{
    print_r("TRIED TO ACCESS UNREACHABLE CODE\n");
    exit(-1);
}

function gen_json_diff($content1, $content2)
{
    $json1 = json_decode($content1, true);
    $json2 = json_decode($content2, true);

    $union = (array_unique(array_merge(array_keys($json1), array_keys($json2))));

    return array_reduce($union, function ($acc, $key) use ($json1, $json2) {
        $type = null;
        if (isset($key, $json1) && !isset($key, $json1)) {
            $type = 'deleted';
        } else if (!isset($key, $json1) && isset($key, $json1)) {
            $type = 'added';
        } else if (isset($key, $json1) && isset($key, $json1)) {
            $type = $json1[$key] === $json2[$key] ? 'unchanged' : 'changed';
        } else {
            unreachable();
        }

        $acc[$key] = [$type, 'value' => $json1[$key]];
        if (isset($key, $json2)) {
            $acc[$key]['value2'] = $json2[$key];
        }

        // switch ($type) {
        //     case 'deleted':
        //         $acc[$key] = [$type, 'value' => $json1[$key]];
        //         break;
        //     case 'added':
        //         $acc[$key] = [$type, 'value' => $json1[$key]];
        //         break;
        //     case 'unchanged':
        //         # code...
        //         break;
        //     case 'changed':
        //         # code...
        //         break;

        //     default:
        //         unreachable();
        //         break;
        // }

        return $acc;
    }, []);
}

function gendiff($filepath1, $filepath2)
{
    $file1contents = file_get_contents($filepath1);
    $file2contents = file_get_contents($filepath2);
    print_r(gen_json_diff($file1contents, $file2contents));
    return gen_json_diff($file1contents, $file2contents);
}
