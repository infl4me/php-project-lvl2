<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

class NestedTest extends TestCase
{
    public function testJsonGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/nested/old.json', './tests/fixtures/nested/new.json'));
        $expected = file_get_contents('./tests/fixtures/nested/expected');

        $this->assertEquals($diff, $expected);
    }

    public function testYmlGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/nested/old.yml', './tests/fixtures/nested/new.yml'));
        $expected = file_get_contents('./tests/fixtures/nested/expected');

        $this->assertEquals($diff, $expected);
    }

    public function testJsonPlainFormatGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/nested/old.json', './tests/fixtures/nested/new.json', 'plain'));
        $expected = file_get_contents('./tests/fixtures/nested/plain_expected');

        $this->assertEquals($diff, $expected);
    }

    public function testYmlPlainFormatGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/nested/old.yml', './tests/fixtures/nested/new.yml', 'plain'));
        $expected = file_get_contents('./tests/fixtures/nested/plain_expected');

        $this->assertEquals($diff, $expected);
    }

    public function testJsonFormatGendiff(): void
    {
        $diff = (\Differ\Differ\genDiff('./tests/fixtures/nested/old.yml', './tests/fixtures/nested/new.yml', 'json'));
        $expected = file_get_contents('./tests/fixtures/nested/json_expected');

        $this->assertEquals($diff, $expected);
    }
}
