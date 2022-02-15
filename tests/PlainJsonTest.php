<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

class PlainJsonTest extends TestCase
{
    public function testGendiff(): void
    {
        $diff = (\gendiff\gendiff('./tests/fixtures/plain/old.json', './tests/fixtures/plain/new.json'));
        $expected = file_get_contents('./tests/fixtures/plain/expected');

        $this->assertEquals($diff, $expected);
    }
}
