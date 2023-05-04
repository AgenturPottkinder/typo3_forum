<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BasicParserService extends AbstractTextParserService
{
    protected string $text;
    private array $protectedParts = [];

    public function getParsedText(string $text, ?Post $post = null): string
    {
        $this->text = $text;
        $this->extractProtectedParts();
        $this->escape();
        $this->regUrls();
        $this->paragraphs()->lineBreaks();
        $this->restoreProtectedParts();

        return $this->text;
    }

    protected function makeUrlClickable(array $matches): string
    {
        $ret = '';
        $url = $matches[2];

        if (empty($url)) {
            return $matches[0];
        }
        // removed trailing [.,;:] from URL
        if (in_array(substr($url, -1), ['.', ',', ';', ':']) === true) {
            $ret = substr($url, -1);
            $url = substr($url, 0, strlen($url) - 1);
        }
        return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
    }

    protected function makeWebFtpClickable(array $matches): string
    {
        $ret = '';
        $dest = $matches[2];
        $dest = 'http://' . $dest;

        if (empty($dest)) {
            return $matches[0];
        }
        // removed trailing [,;:] from URL
        if (in_array(substr($dest, -1), ['.', ',', ';', ':']) === true) {
            $ret = substr($dest, -1);
            $dest = substr($dest, 0, strlen($dest) - 1);
        }
        return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
    }

    protected function makeEmailClickable(array $matches): string
    {
        $email = $matches[2] . '@' . $matches[3];
        return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
    }

    protected function regUrls(): self
    {
        $ret = ' ' . $this->text;
        // in testing, using arrays here was found to be faster
        $ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', [&$this, 'makeUrlClickable'], $ret);
        $ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', [&$this, 'makeWebFtpClickable'], $ret);
        $ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', [&$this, 'makeEmailClickable'], $ret);

        // this one is not in an array because we need it to run last, for cleanup of accidental links within links
        $ret = preg_replace('#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', '$1$3</a>', $ret);
        $ret = trim($ret);
        $this->text = $ret;

        return $this;
    }

    /**
     * Extracts all protected parts from the text and replaces them with placeholders.
     */
    protected function extractProtectedParts(): self
    {
        $pattern = ',\[code language=[a-z0-9]+\](.*?)\[\/code\],is';
        preg_match_all($pattern, $this->text, $this->protectedParts);
        $this->text = preg_replace($pattern, '###MMFORUM_PROTECTED###', $this->text);

        return $this;
    }

    /**
     * Replaces all placeholders for protected parts with the original contents.
     */
    protected function restoreProtectedParts(): self
    {
        while (($s = strpos($this->text, '###MMFORUM_PROTECTED###')) !== false) {
            $this->text = substr_replace($this->text, array_shift($this->protectedParts[0]), $s, strlen('###MMFORUM_PROTECTED###'));
        }

        return $this;
    }

    /**
     * Performs simple HTML escaping on the text.
     */
    protected function escape(): self
    {
        $this->text = htmlspecialchars($this->text);
        return $this;
    }

    /**
     * Replaces double line breaks with paragraphs.
     */
    protected function paragraphs(): self
    {
        $this->text = str_replace("\r", '', $this->text);
        $this->text = preg_replace(';\n{2,};s', "\n\n", $this->text);

        $paragraphs = GeneralUtility::trimExplode("\n\n", $this->text);
        $this->text = '<p>' . implode('</p><p>', $paragraphs) . '</p>';
        return $this;
    }

    /**
     * Replaces single line breaks with <br> tags.
     */
    protected function lineBreaks(): self
    {
        $this->text = $this->removeUnneccesaryLinebreaks($this->text);
        $this->text = nl2br($this->text);
        return $this;
    }

    /**
     * Removes superflous line breaks within the text.
     */
    protected function removeUnneccesaryLinebreaks(string $text): string
    {
        $text = preg_replace(',(\[[a-z0-9 ]+\])\s*,is', '$1', $text);
        return $text;
    }
}
