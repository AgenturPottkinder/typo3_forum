<?php
namespace Mittwald\Typo3Forum\TextParser\Panel;

use Mittwald\Typo3Forum\Domain\Model\Format\Smiley;
use Mittwald\Typo3Forum\Domain\Repository\Format\SmileyRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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

class SmileyPanel extends AbstractPanel
{
    /**
     * @var \Mittwald\Typo3Forum\Domain\Model\Format\Smiley[]|null
     */
    protected ?array $smileys = null;

    public function getItems(): ?array
    {
        if ($this->smileys === null) {
            $this->smileys = GeneralUtility::makeInstance(SmileyRepository::class)
                ->findAll()->toArray()
            ;
        }

        if (count($this->smileys) === 0) {
            return null;
        }

        $result = array_map(
            function (Smiley $smiley): array
            {
                return $smiley->exportForMarkItUp();
            },
            $this->smileys
        );

        return [[
            'name' => LocalizationUtility::translate($this->settings['title']),
            'className' => $this->settings['iconClassName'],
            'replaceWith' => $this->smileys[0]->getSmileyShortcut(),
            'dropMenu' => $result
        ]];
    }
}
