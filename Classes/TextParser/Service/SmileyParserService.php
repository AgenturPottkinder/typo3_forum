<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

use Mittwald\Typo3Forum\Domain\Model\Format\Smiley;

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

use TYPO3\CMS\Extbase\Annotation\Inject;

class SmileyParserService extends AbstractTextParserService
{

    /**
     * @var \Mittwald\Typo3Forum\Domain\Repository\Format\SmileyRepository
     * @Inject
     */
    protected $smileyRepository;

    /**
     * All smileys.
     * @var array<\Mittwald\Typo3Forum\Domain\Model\Format\Smiley>
     */
    protected $smileys;

    /**
     * Renders the parsed text.
     *
     * @param string $text The text that is to be parsed.
     * @param Post $post The post object
     * @return string       The parsed text.
     */
    public function getParsedText($text, $post = null)
    {
        if ($this->smileys === null) {
            $this->smileys = $this->smileyRepository->findAll();
        }
        foreach ($this->smileys as $smiley) {
            if (':/' === $smiley->getSmileyShortcut()) {
                $lastPos = 0;
                while (($lastPos = strpos($text, $smiley->getSmileyShortcut(), $lastPos)) !== false) {
                    $before =substr($text, $lastPos-4, 4);
                    $currentPos = $lastPos;
                    $lastPos = $lastPos + strlen($smiley->getSmileyShortcut());
                    if (($before === 'http') || ($before === 'ttps')) {
                        continue;
                    }
                    $text = substr_replace($text, $this->getSmileyIcon($smiley), $currentPos, strlen($smiley->getSmileyShortcut()));
                }
            } else {
                $text = str_replace($smiley->getSmileyShortcut(), $this->getSmileyIcon($smiley), $text);
            }
        }
        return $text;
    }

    /**
     * Renders a smiley icon.
     *
     * @param Smiley $smiley The smiley that is to be rendered.
     *
     * @return string The smiley as HTML code.
     */
    protected function getSmileyIcon(Smiley $smiley)
    {
        return '<i class="' . $smiley->getIconClass() . '"></i>';
    }
}
