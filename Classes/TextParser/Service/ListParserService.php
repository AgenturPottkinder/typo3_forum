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

/**
 * Text parser class for parsing complex lists.
 */
class ListParserService extends AbstractTextParserService
{
    /**
     * The regular expression for matching lists.
     * @var string
     */
    const PREG_MATCH_LIST = ',\[list\]\s*(.*?)\s*\[\/list\],is';

    /**
     * Parses lists inside a text.
     */
    public function getParsedText(string $text, ?Post $post = null): string
    {
        $callback = function ($matches) {
            $items = array_filter(GeneralUtility::trimExplode('[*]', $matches[1]));
            return '<ul><li>' . implode('</li><li>', $items) . '</li></ul>';
        };
        return preg_replace_callback(self::PREG_MATCH_LIST, $callback, $text);
    }
}
