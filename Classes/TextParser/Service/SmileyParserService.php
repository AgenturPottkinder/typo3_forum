<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

use Mittwald\Typo3Forum\Domain\Model\Format\Smiley;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Repository\Format\SmileyRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
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


class SmileyParserService extends AbstractTextParserService
{
    protected SmileyRepository $smileyRepository;

    /**
     * @var Smiley[]
     */
    protected array $smileys = [];

    public function __construct(SmileyRepository $smileyRepository)
    {
        $this->smileyRepository = $smileyRepository;
    }

    /**
     * Renders the parsed text.
     */
    public function getParsedText(string $text, ?Post $post = null): string
    {
        if (count($this->smileys) === 0) {
            $this->smileys = $this->smileyRepository->findAll()->toArray();
        }
        foreach ($this->smileys as $smiley) {
            if (':/' === $smiley->getSmileyShortcut()) {
                // Prevent ":/" smiley from messing up URLs.
                $lastPos = 0;
                while (($lastPos = strpos($text, $smiley->getSmileyShortcut(), $lastPos)) !== false) {
                    $before = substr($text, $lastPos-4, 4);
                    $currentPos = $lastPos;
                    $lastPos = $lastPos + strlen($smiley->getSmileyShortcut());
                    if (($before === 'http') || ($before === 'ttps')) {
                        continue;
                    }
                    $text = substr_replace($text, $this->getSmileyIcon($smiley), $currentPos, strlen($smiley->getSmileyShortcut()));
                }
            } else {
                $text = str_replace(htmlentities($smiley->getSmileyShortcut()), $this->getSmileyIcon($smiley), $text);
            }
        }
        return $text;
    }

    /**
     * Renders a smiley icon.
     */
    protected function getSmileyIcon(Smiley $smiley): string
    {
        return '<i class="tx-typo3forum-smiley"><img src="'
            . $this->resolveIconPath($smiley->getImagePath())
            . '" /></i>'
        ;
    }

    protected function resolveIconPath(string $iconPath): string
    {
        return PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($iconPath));
    }
}
