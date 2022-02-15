<?php

namespace gendiff\cli;

use Docopt;

const doc = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help     Show this screen.
  --version     Show version.

DOC;

function cli()
{
    $params = array(
        'version' => '1.0',
    );
    $args = Docopt::handle(doc, $params);
}
