<?php
/**
 *
 * COPYRIGHT NOTICE
 *
 *  (c) 2017 Mittwald CM Service GmbH & Co KG
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

namespace Mittwald\Typo3Forum\Service\Migration;


use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserGroupMigrationService extends AbstractMigrationService
{
    const EXTBASE_TYPE = FrontendUserGroup::class;

    /**
     * @inheritdoc
     */
    public function migrate()
    {
        if (!($userPid = GeneralUtility::_POST('user_pid'))) {
            $this->addMessage(FlashMessage::ERROR, 'COULD NOT MIGRATE USER GROUPS', 'NO PID FOR USER GROUPS GIVEN');
        }

        if (($userGroups = $this->getUserGroups($userPid))) {
            foreach ($userGroups as $userGroup) {
                $this->updateUserGroup($userGroup);
            }

            $this->addMessage(
                FlashMessage::OK, 'MIGRATE ' . $this->getTitle(), 'MIGRATED ' . $this->getTitle() . 'ENTRIES'
            );
        }


        return $this->generateOutput();
    }

    /**
     * @param $pid
     * @return bool|\mysqli_result|object
     */
    protected function getUserGroups($pid)
    {
        return $this->databaseConnection->exec_SELECTquery('*', $this->getOldTableName(), 'pid = ' . (int)$pid);
    }

    /**
     * @param array $userGroup
     */
    protected function updateUserGroup(array $userGroup)
    {
        $updateFields['tx_extbase_type'] = self::EXTBASE_TYPE;
        $this->databaseConnection->exec_UPDATEquery(
            $this->getNewTableName(), 'uid = ' . $userGroup['uid'], $updateFields
        );
    }


    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'fe_groups';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'fe_groups';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'USERGROUPS';
    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [];
    }
}