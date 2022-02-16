<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

class NestedTest extends TestCase
{
    public function testJsonGendiff(): void
    {
        $diff = (\gendiff\gendiff('./tests/fixtures/nested/old.json', './tests/fixtures/nested/new.json'));
        $expected = file_get_contents('./tests/fixtures/nested/expected');

        $this->assertEquals($diff, $expected);
    }

    public function testYmlGendiff(): void
    {
        $diff = (\gendiff\gendiff('./tests/fixtures/nested/old.yml', './tests/fixtures/nested/new.yml'));
        $expected = file_get_contents('./tests/fixtures/nested/expected');

        $this->assertEquals($diff, $expected);
    }

    public function testJsonPlainFormatGendiff(): void
    {
        $diff = (\gendiff\gendiff('./tests/fixtures/nested/old.json', './tests/fixtures/nested/new.json', 'plain'));
        $expected = file_get_contents('./tests/fixtures/nested/plain_expected');

        $this->assertEquals($diff, $expected);
    }

    public function testYmlPlainFormatGendiff(): void
    {
        $diff = (\gendiff\gendiff('./tests/fixtures/nested/old.yml', './tests/fixtures/nested/new.yml', 'plain'));
        $expected = file_get_contents('./tests/fixtures/nested/plain_expected');

        $this->assertEquals($diff, $expected);
    }
}
