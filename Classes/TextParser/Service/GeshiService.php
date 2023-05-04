<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

include_once(__DIR__ . '/../../../Resources/Private/Libraries/GeSHi/geshi.php');

class GeshiService
{
    public function getFormattedText(
        string $sourceCode,
        string $language = 'php'
    ): string {
        $language = strtolower($language);

        $geshi = new \GeSHi($sourceCode, $language);
        $geshi->enable_strict_mode(false);
        $geshi->enable_line_numbers(true, 2);
        return $geshi->parse_code();
    }
}
