<?php
namespace Mittwald\Typo3Forum\Domain\Factory;

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

abstract class AbstractFactory implements SingletonInterface
{
    protected FrontendUserRepository $frontendUserRepository;
    protected ConfigurationBuilder $configurationBuilder;

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository): void
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }
    public function injectConfigurationBuilder(ConfigurationBuilder $configurationBuilder): void
    {
        $this->configurationBuilder = $configurationBuilder;
    }

    protected array $settings = [];

    public function initializeObject()
    {
        $this->settings = $this->configurationBuilder->getSettings();
    }

    /**
     * Determines the class name of the domain object this factory is used for.
     *
     * @return string The class name
     */
    protected function getClassName()
    {
        $thisClass = get_class($this);
        $thisClass = preg_replace('/Factory/', 'Model', $thisClass);
        $thisClass = preg_replace('/Model$/', '', $thisClass);

        return $thisClass;
    }

    /**
     * Creates an instance of the domain object class.
     *
     * @return AbstractDomainObject An instance of the domain object.
     */
    protected function getClassInstance()
    {
        return GeneralUtility::makeInstance($this->getClassName());
    }

    /**
     * Gets the currently logged in user. Convenience wrapper for the findCurrent
     * method of the frontend user repository.
     *
     * @return FrontendUser The user that is currently logged in.
     */
    protected function getCurrentUser()
    {
        return $this->frontendUserRepository->findCurrent();
    }
}
