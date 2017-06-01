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

/**
 * Created by PhpStorm.
 * User: kpurrmann
 * Date: 28.02.17
 * Time: 14:45
 */

namespace Mittwald\Typo3Forum\Service\Migration;


use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractMigrationService
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     *
     */
    public function initializeObject()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * @return array
     */
    abstract public function getFieldsDefinition();

    /**
     * @return string
     */
    abstract public function getOldTableName();

    /**
     * @return string
     */
    abstract public function getNewTableName();

    /**
     * @return string
     */
    abstract public function getTitle();


    /**
     * @return string
     */
    public function migrate()
    {
        $this->truncateNewTable();

        $this->migrateTable(
            $this->getOldTableName(),
            $this->getNewTableName(),
            $this->getFieldsDefinition(),
            array_keys($this->getFieldsDefinition()),
            $this->getTitle()
        );

        return $this->generateOutput();
    }

    /**
     * @return void
     */
    protected function truncateNewTable()
    {
        $this->databaseConnection->exec_TRUNCATEquery($this->getNewTableName());
    }

    /**
     * @return array
     */
    protected function getCurrentTables()
    {
        return $this->databaseConnection->admin_get_tables();
    }

    /**
     * @param $oldTable
     * @param $newTable
     * @param array $newFields
     * @param array $fields
     * @param $title
     */
    protected function migrateTable(
        $oldTable,
        $newTable,
        array $newFields,
        array $fields,
        $title
    ) {
        if (array_key_exists($oldTable, $this->getCurrentTables())) {
            $result = $this->databaseConnection->admin_query(
                'INSERT INTO ' . $newTable . ' (' . implode(',', $newFields) . ') SELECT ' . implode(
                    ',', $fields
                ) . ' FROM ' . $oldTable
            );

            if ($result) {
                $this->addMessage(
                    FlashMessage::OK,
                    'MIGRATED ' . $title,
                    'MIGRATED ' . $title . ' ENTRIES'
                );
            } else {
                $this->addMessage(
                    FlashMessage::ERROR,
                    'ERROR ON MIGRATION OF ' . $title,
                    $this->databaseConnection->sql_error()
                );
            }
        }
    }


    /**
     * Add flash message to message array
     *
     * @param $status
     * @param $title
     * @param $message
     */
    protected function addMessage($status, $title, $message)
    {
        array_push($this->messages, array($status, $title, $message));
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        if (!empty($this->messages)) {

            foreach ($this->messages as $messageItem) {
                /** @var FlashMessage $flashMessage */
                $flashMessage = GeneralUtility::makeInstance(
                    'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                    $messageItem[2],
                    $messageItem[1],
                    $messageItem[0]
                );

                $this->getFlashMessageService()
                    ->getMessageQueueByIdentifier()
                    ->addMessage($flashMessage);
            }
        }

        return $this->getFlashMessageService()
            ->getMessageQueueByIdentifier()
            ->renderFlashMessages();
    }

    /**
     * @return object|FlashMessageService
     */
    private function getFlashMessageService()
    {
        return $this->objectManager->get(FlashMessageService::class);
    }

}