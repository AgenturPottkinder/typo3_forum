<?php
namespace Mittwald\Typo3Forum\Domain\Repository\User;

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

use Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield;
use Mittwald\Typo3Forum\Domain\Repository\AbstractRepository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Repository class for additional userfields. Unlike other repositories,
 * this one creates part of its data not from the database, but load it
 * from the extension's typoscript configuration instead.
 *
 * This is done e.g. for the so-called core-userfields, that are to be
 * displayed in the user profile view independently of whether they are
 * configured in the database.
 */
class UserfieldRepository extends AbstractRepository
{
    protected ConfigurationManagerInterface $configurationManager;

    /** @var AbstractUserfield[] $coreUserfields */
    protected array $coreUserfields = [];

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
        $this->objectType = AbstractUserfield::class;
    }

    /*
     * REPOSITORY METHODS
     */

    /**
     * Finds all userfields. This method loads all userfields from the database
     * and merges the result with the core userfields that are loaded from the
     * typoscript setup.
     *
     * @return \Traversable<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield>
     *                             All userfields, both from the database and
     *                             the core typoscript setup.
     */
    public function findAll()
    {
        $query = $this->createQueryWithFallbackStoragePage();

        return array_merge($this->findCoreUserfields(), $query->execute()->toArray());
    }

    /**
     * Finds all core userfields. These are stored in the typoscript setting
     * plugin.tx_typo3forum.settings.userfields.core_fields.
     *
     * @return array|\Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield
     *     The core userfields that are generated from the
     *     typoscript configuration.
     * @throws \TYPO3\CMS\Extbase\Object\UnknownClassException
     */
    protected function findCoreUserfields()
    {
        if (count($this->coreUserfields) === 0) {
            $conf = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->coreUserfields = [];

            foreach ($conf['settings']['userfields']['core_fields'] as $coreFieldKey => $coreFieldValues) {
                $className = $coreFieldValues['class'];
                if (!class_exists($className)) {
                    throw new \TYPO3\CMS\Extbase\Object\UnknownClassException("The class $className does not exist!", 1287756385);
                }

                $object = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($className);

                if (!$object instanceof \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield) {
                    throw new \TYPO3\CMS\Extbase\Object\UnknownClassException("The class $className is not a subclass of \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield", 1287756386);
                }

                foreach ($coreFieldValues['properties'] as $propertyName => $propertyValue) {
                    if ($object->_hasProperty($propertyName)) {
                        $object->_setProperty($propertyName, $propertyValue);
                    }
                }
                $this->coreUserfields[] = $object;
            }
        }

        return $this->coreUserfields;
    }
}
