<?php

namespace Tests\Unit;

use App\Services\Highlighter;
use App\Support\StringUtils;
use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
    /** @dataProvider providerToFilename */
    public function testToValidFilename(string $input, bool $isValid, ?string $expected = null)
    {
        $result = StringUtils::toFilename($input, 'fallback');

        $expectedResult = 'fallback';
        if ($isValid) {
            $expectedResult = $expected ?? $input;
        }

        $this->assertSame($expectedResult, $result);
    }

    public function providerToFilename(): array
    {
        return [
            ['foo', true],
            ['bar.bar', true],
            ['Another Filename - Test', true],
            ['yet_another.file', true],
            ['zażółć gęślą jaźń', true],
            ['.foo', true, 'foo'],
            ['foo.', true, 'foo'],
            ['.', false],
            ['NUL', false],
            ['nul', false],
            ['NUL test', true],
        ];
    }
}
