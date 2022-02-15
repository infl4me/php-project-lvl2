<?php

namespace gendiff\cli;

use Docopt;
use function gendiff\gendiff;

const doc = <<<DOC
Generate diff

Usage:
  gendiff <f1> <f2>
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>]

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;

function cli()
{
    $params = ['version' => '1.0'];
    ['<f1>' => $filepath1, '<f2>' => $filepath2, '--format' => $format] = Docopt::handle(doc, $params);

    if ($filepath1 && $filepath2) {
        print_r(gendiff($filepath1, $filepath2));
    }
}
