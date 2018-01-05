<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2017 Mittwald CM Service GmbH & Co KG                           *
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

namespace Mittwald\Typo3Forum\Service;


use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

class SettingsHydrator
{
    /**
     * @var ConfigurationBuilder
     */
    private $configurationBuilder;

    /**
     * injectConfigurationBuilder.
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function injectConfigurationBuilder(\Mittwald\Typo3Forum\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }

    /**
     * hydrateSettings.
     * @param DomainObjectInterface $object
     */
    public function hydrateSettings(DomainObjectInterface $object)
    {
        if ($object instanceof ConfigurableInterface) {
            $object->injectSettings($this->configurationBuilder);
        }
    }
}
