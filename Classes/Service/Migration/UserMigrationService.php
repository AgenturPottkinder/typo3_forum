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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserMigrationService extends AbstractMigrationService
{
    const EXTBASE_TYPE = FrontendUser::class;

    /**
     * @inheritdoc
     */
    public function migrate()
    {
        if (!($userPid = GeneralUtility::_POST('user_pid'))) {
            $this->addMessage(FlashMessage::ERROR, 'COULD NOT MIGRATE USERS', 'NO PID FOR USERS GIVEN');
        }

        $this->changeTableDefinition();


        if (($users = $this->getUsers($userPid))) {
            foreach ($users as $user) {
                $this->updateUser($user);
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
    protected function getUsers($pid)
    {
        return $this->databaseConnection->exec_SELECTquery('*', $this->getOldTableName(), 'pid = ' . (int)$pid);
    }

    /**
     * @param array $user
     */
    protected function updateUser(array $user)
    {
        // The inputs of array_combine must be sorted to avoid wrong assignment
        $sortedFieldDefinitions = $this->getFieldsDefinition();
        ksort($sortedFieldDefinitions);

        $fields = array_intersect_key($user, $sortedFieldDefinitions);
        ksort($fields);

        $updateFields = array_combine($sortedFieldDefinitions, array_values($fields));
        $updateFields['tx_extbase_type'] = self::EXTBASE_TYPE;
        $this->databaseConnection->exec_UPDATEquery($this->getNewTableName(), 'uid = ' . $user['uid'], $updateFields);
    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [
            'tx_mmforum_avatar' => 'image',
            'tx_mmforum_user_sig' => 'tx_typo3forum_signature',
            'tx_mmforum_skype' => 'tx_typo3forum_skype',
        ];
    }

    /**
     * @return array
     */
    public function addFieldsDefinition()
    {
        return [
            'tx_typo3forum_signature' => 'TEXT NULL',
            'tx_typo3forum_skype' => 'VARCHAR(255) NULL',
        ];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'fe_users';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'fe_users';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'USERS';
    }

    /**
     *
     */
    protected function changeTableDefinition()
    {

        $exampleRow = current(
            $this->databaseConnection->exec_SELECTgetRows('*', $this->getNewTableName(), '1=1', '', '', 1)
        );
        if ($fields = array_diff_key($this->addFieldsDefinition(), $exampleRow)) {
            foreach ($fields as $field => $definition) {
                $this->databaseConnection->admin_query(
                    'ALTER TABLE ' . $this->getNewTableName() . ' ADD ' . $field . ' ' . $definition . ';'
                );
            }
        }
    }

}