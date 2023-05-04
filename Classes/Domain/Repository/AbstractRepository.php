<?php
namespace Mittwald\Typo3Forum\Domain\Repository;

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

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Abstract base class for all typo3_forum repositories.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
abstract class AbstractRepository extends Repository
{
    protected ConfigurationBuilder $configurationBuilder;
    protected array $settings = [];
    protected array $persistenceSettings = [];

    public function injectConfigurationBuilder(ConfigurationBuilder $configurationBuilder): void
    {
        $this->configurationBuilder = $configurationBuilder;
    }

    public function initializeObject(): void
    {
        $this->settings = $this->configurationBuilder->getSettings();
        $this->persistenceSettings = $this->configurationBuilder->getPersistenceSettings();

        if (isset($this->persistenceSettings['storagePid'])) {
            $this->setDefaultQuerySettings(
                $this->getQuerySettings()->setStoragePageIds(explode(',', $this->persistenceSettings['storagePid']))
            );
        }
    }

    protected function createQueryWithFallbackStoragePage(): QueryInterface
    {
        $query = $this->createQuery();

        $storagePageIds = $query->getQuerySettings()->getStoragePageIds();
        $storagePageIds[] = 0;

        $query->getQuerySettings()->setStoragePageIds($storagePageIds);

        return $query;
    }

    protected function getQuerySettings(): QuerySettingsInterface
    {
        return GeneralUtility::makeInstance(QuerySettingsInterface::class);
    }
}
