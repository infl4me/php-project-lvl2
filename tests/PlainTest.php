<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

class PlainTest extends TestCase
{
    public function testJsonGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/plain/old.json', './tests/fixtures/plain/new.json'));
        $expected = file_get_contents('./tests/fixtures/plain/expected');

        $this->assertEquals($diff, $expected);
    }

    public function testYmlGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/plain/old.yml', './tests/fixtures/plain/new.yml'));
        $expected = file_get_contents('./tests/fixtures/plain/expected');

        $this->assertEquals($diff, $expected);
    }
}
