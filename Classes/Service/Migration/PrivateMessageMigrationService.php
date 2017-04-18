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


use TYPO3\CMS\Core\Messaging\FlashMessage;

class PrivateMessageMigrationService extends AbstractMigrationService
{

    /**
     * @inheritdoc
     */
    public function migrate()
    {
        $this->truncateNewTable();
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_user_privatemessage_text');

        if (($oldMessages = $this->getOldPrivateMessages())) {
            foreach ($oldMessages as $oldMessage) {
                if (($messageId = $this->persistPrivateMessageText($oldMessage['message'], $oldMessage['pid']))) {
                    $this->persistPrivateMessage($oldMessage, $messageId);
                }
            }
            $this->addMessage(
                FlashMessage::OK, 'MIGRATED' . $this->getTitle(), 'MIGRATED ' . $this->getTitle() . ' ENTRIES'
            );
        }

        return $this->generateOutput();

    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [
            'uid' => 'uid',
            'pid' => 'pid',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'from_uid' => 'feuser',
            'to_uid' => 'opponent',
            'mess_type' => 'type',
            'read_flg' => 'user_read',
        ];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'tx_mmforum_pminbox';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'tx_typo3forum_domain_model_user_privatemessage';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'PRIVATE MESSAGES';
    }

    /**
     * @return array|NULL
     */
    protected function getOldPrivateMessages()
    {
        return $this->databaseConnection->exec_SELECTgetRows('*', $this->getOldTableName(), '1=1');
    }

    /**
     * @param array $message
     * @param $messageId
     */
    protected function persistPrivateMessage(array $message, $messageId)
    {

        $fields = array_intersect_key($message, $this->getFieldsDefinition());
        $updateFields = array_combine($this->getFieldsDefinition(), array_values($fields));
        $updateFields['message'] = $messageId;
        $updateFields['type'] = (int)$updateFields['type'];
        $this->databaseConnection->exec_INSERTquery($this->getNewTableName(), $updateFields);
    }

    /**
     * @param $message
     * @param $pid
     * @return int
     */
    protected function persistPrivateMessageText($message, $pid)
    {
        $fields = ['pid' => $pid, 'message_text' => $message];
        $this->databaseConnection->exec_INSERTquery('tx_typo3forum_domain_model_user_privatemessage_text', $fields);

        return $this->databaseConnection->sql_insert_id();
    }
}