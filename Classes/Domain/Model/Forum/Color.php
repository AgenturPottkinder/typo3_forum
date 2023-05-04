<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                    - *
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Color extends AbstractEntity
{
    protected string $name  = '';
    protected string $primaryColor  = '';
    protected string $secondaryColor  = '';

    public static function getDefaultColor(): Color
    {
        return GeneralUtility::makeInstance(Color::class)
            ->setName('Default')
            ->setPrimaryColor('#222')
            ->setSecondaryColor('#ff8000')
        ;
    }

    public function getSecondaryColor(): string
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(string $secondaryColor): self
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }

    public function getPrimaryColor(): string
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor(string $primaryColor): self
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
