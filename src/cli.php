<?php

namespace gendiff\cli;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <file1> <file2>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;

function cli()
{
    $params = ['version' => '1.0'];
    ['<file1>' => $filepath1, '<file2>' => $filepath2, '--format' => $format] = \Docopt::handle(DOC, $params);

    if ($filepath1 && $filepath2) {
        print_r(\gendiff\gendiff($filepath1, $filepath2, $format));
    } else {
        print_r(DOC);
    }
}
