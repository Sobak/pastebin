<?php

namespace App\Services;

use Kadet\Highlighter\Formatter\HtmlFormatter;
use Kadet\Highlighter\KeyLighter;

class Highlighter
{
    /** @var KeyLighter */
    protected $keylighter;

    public function __construct()
    {
        $this->keylighter = new KeyLighter();
        $this->keylighter->init();
    }

    public function highlight($string, $language = null)
    {
        $formatter = new HtmlFormatter(['lines' => ['enable' => true]]);
        $language = $this->keylighter->getLanguage($language ?? 'text');

        $source = $this->keylighter->highlight($string, $language, $formatter);

        return $this->lineify($source);
    }

    public function normalizeLanguageName($name)
    {
        return $this->keylighter->getLanguage($name)->getIdentifier();
    }

    public function getLanguageNameByMime($mime)
    {
        return $this->keylighter->languageByMime($mime)->getIdentifier();
    }

    public function getExtensionByLanguageName($language)
    {
        $defaultExtension = '.txt';
        $language = $this->keylighter->languageByName($language);
        $metadata = $language::getMetadata();

        if (isset($metadata['extension']) === false) {
            return $defaultExtension;
        }

        $extensions = $metadata['extension'];

        if (empty($extensions)) {
            return $defaultExtension;
        }

        foreach ($extensions as $candidate) {
            if (preg_match('#\*?(?P<suffix>\.[^*?]+)#', $candidate, $match)) {
                return $match['suffix'];
            }
        }

        return $defaultExtension;
    }

    protected function lineify($source)
    {
        $no = 1;
        $result = '';

        foreach (preg_split('/\R/u', $source) as $i => $line) {
            $result .= sprintf(
                '<div class="line" id="L%d"><code><span class="counter" data-ln="%d"></span>%s' . "\n" . '</code></div>',
                $no, $no, $line
            );

            $no++;
        }

        return $result;
    }
}
