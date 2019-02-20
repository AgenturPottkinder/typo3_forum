<?php
/**
 *
 * COPYRIGHT NOTICE
 *
 *  (c) 2018 Mittwald CM Service GmbH & Co KG
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published
 *  by the Free Software Foundation; either version 2 of the License,
 *  or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */

declare(strict_types=1);


namespace Mittwald\Typo3Forum\Scheduler;


use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class AbstractDatabaseTask
 * @package Mittwald\Typo3Forum\Scheduler
 * @copyright 2018 Kevin Purrmann
 */
abstract class AbstractDatabaseTask extends AbstractTask
{


    /**
     * getDatabaseConnection.
     * @param string $table
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getDatabaseConnection($table = 'tx_typo3forum_domain_model_forum_post')
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($table);
    }


    /**
     * getConnectionPool.
     * @return ConnectionPool|object
     */
    protected function getConnectionPool()
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}