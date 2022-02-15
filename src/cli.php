<?php

namespace gendiff\cli;

use Docopt;

const doc = <<<DOC
Generate diff

Usage:
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
    $params = array(
        'version' => '1.0',
    );
    $args = Docopt::handle(doc, $params);
}
