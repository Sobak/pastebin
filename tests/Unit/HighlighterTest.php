<?php

namespace Tests\Unit;

use App\Services\Highlighter;
use PHPUnit\Framework\TestCase;

class HighlighterTest extends TestCase
{
    /**
     * @dataProvider providerGetExtensionByLanguageName
     */
    public function testGetExtensionByLanguageName($language, $extension)
    {
        $highlighter = new Highlighter();

        $this->assertSame($extension, $highlighter->getExtensionByLanguageName($language));
    }

    public function providerGetExtensionByLanguageName()
    {
        return [
            ['c', '.c'],
            ['bash', '.sh'],
            ['apache', '.htaccess'],
            ['text', '.txt'],
            ['plaintext', '.txt'],
        ];
    }
}
