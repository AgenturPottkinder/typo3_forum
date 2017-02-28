<?php
/**
 *
 * COPYRIGHT NOTICE
 *
 *  (c) 2016 Mittwald CM Service GmbH & Co KG
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

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ext_update
 *
 * @todo Actually should use defined entity repositories but in case
 * @todo they depend on too much services it is not possible to init one of the repositories
 */
class ext_update
{

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @var array
     */
    protected $currentTables = array();

    /**
     * ext_update constructor.
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->currentTables = $this->databaseConnection->admin_get_tables();
    }

    /**
     * @return bool
     */
    public function access()
    {
        return true;
    }

    public function main()
    {
        $this->processUpdates();

        return $this->generateOutput();
    }

    /**
     * @return void
     */
    private function processUpdates()
    {
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_forum_access');
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_forum_forum');
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_forum_topic');
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_forum_post');
        $this->databaseConnection->exec_TRUNCATEquery('tx_typo3forum_domain_model_forum_attachment');
        $this->migrateForums();
        $this->migrateTopics();
        $this->migratePosts();
        $this->migrateAttachments();
    }

    /**
     *
     */
    private function migrateAttachments()
    {
        $fields = array(
            'uid' => 'uid',
            'pid' => 'pid',
            'file_type' => 'mime_type',
            'file_name' => 'filename',
            'file_path' => 'real_filename',
            'downloads' => 'download_count',
            'post_id' => 'post',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'hidden' => 'hidden',
        );

        $this->migrateTable('tx_mmforum_attachments', 'tx_typo3forum_domain_model_forum_attachment', $fields,
            array_keys($fields),
            'attachments');
    }

    private function migratePosts()
    {

        if (($posts = $this->databaseConnection->exec_SELECTquery('*', 'tx_mmforum_posts', '1=1'))) {
            foreach ($posts as $post) {
                $text = $this->databaseConnection->exec_SELECTgetSingleRow('*', 'tx_mmforum_posts_text',
                    'post_id = '.$post['uid']);

                $newPost = [
                    'uid' => $post['uid'],
                    'pid' => $post['pid'],
                    'topic' => $post['topic_id'],
                    'text' => $text['post_text'],
                    'rendered_text' => $text['cache_text'],
                    'author' => $post['poster_id'],
                    'tstamp' => $post['tstamp'],
                    'crdate' => $post['crdate'],
                    'deleted' => $post['deleted'],
                    'hidden' => $post['hidden'],
                    'attachments' => $post['attachment'],
                ];

                $this->databaseConnection->exec_INSERTquery('tx_typo3forum_domain_model_forum_post', $newPost);
            }

            $this->addMessage(\TYPO3\CMS\Core\Messaging\FlashMessage::OK, 'MIGRATED POSTS',
                'MIGRATED POSTS ENTRIES');
        }
    }

    private function migrateTopics()
    {
        $fields = array(
            'uid' => 'uid',
            'pid' => 'pid',
            'topic_title' => 'subject',
            'topic_poster' => 'author',
            'topic_last_post_id' => 'last_post',
            'forum_id' => 'forum',
            'solved' => 'is_solved',
            'closed_flag' => 'closed',
            'at_top_flag' => 'sticky',
            'topic_views' => 'readers',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'hidden' => 'hidden',
        );

        $this->migrateTable('tx_mmforum_topics', 'tx_typo3forum_domain_model_forum_topic', $fields, array_keys($fields),
            'topics');
    }

    /**
     * @return void
     */
    private function migrateForums()
    {

        // SELECT EACH FORUM
        if (($result = $this->selectEntities('tx_mmforum_forums'))) {
            foreach ($result as $row) {
                $this->createForumRelations($row);
            }
        }

        $fields = array(
            'uid' => 'uid',
            'pid' => 'pid',
            'forum_name' => 'title',
            'forum_desc' => 'description',
            'forum_topics' => 'topics',
            'forum_last_post_id' => 'last_post',
            'parentID' => 'forum',
            'sorting' => 'sorting',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'hidden' => 'hidden',
        );

        $this->migrateTable('tx_mmforum_forums', 'tx_typo3forum_domain_model_forum_forum', $fields, array_keys($fields),
            'forums');
    }

    /**
     * @param array $row
     */
    private function createForumRelations(array $row)
    {
        $this->addACL($row, 'group', 'read', 2);
        $this->addACL($row, 'group', 'write', 2);
        $this->addACL($row, 'group', 'mod', 2);

    }

    /**
     * @param array $row
     * @param $role
     * @param $operation
     * @param $loginLevel
     */
    private function addACL(array $row, $role, $operation, $loginLevel)
    {
        if (($row[$role.'rights_'.$operation])) {
            $groups = GeneralUtility::trimExplode(',', $row[$role.'rights_'.$operation]);
            foreach ($groups as $group) {
                $acl['pid'] = $row['pid'];
                $acl['login_level'] = $loginLevel;
                $acl['forum'] = $row['uid'];
                $acl['affected_group'] = $group;

                if ($operation === 'write') {
                    $this->persistACL($acl, 'newPost');
                    $this->persistACL($acl, 'newTopic');
                } elseif ($operation === 'mod') {
                    $this->persistACL($acl, 'editPost');
                    $this->persistACL($acl, 'deletePost');
                    $this->persistACL($acl, 'moderate');
                } else {
                    $this->persistACL($acl, $operation);
                }
            }
        }
    }

    /**
     * @param array $acl
     * @param $operation
     */
    private function persistACL(array $acl, $operation)
    {
        $acl['operation'] = $operation;
        $this->databaseConnection->exec_INSERTquery('tx_typo3forum_domain_model_forum_access', $acl);
    }

    /**
     * @param $table
     * @return bool|mysqli_result|object
     */
    private function selectEntities($table)
    {
        return $this->databaseConnection->exec_SELECTquery('*', $table, '1=1', '', 'parentID ASC');
    }


    /**
     * @param $oldTable
     * @param $newTable
     * @param array $newFields
     * @param array $fields
     * @param $title
     */
    private function migrateTable($oldTable, $newTable, array $newFields, array $fields, $title)
    {
        if (array_key_exists($oldTable, $this->currentTables)) {
            $this->databaseConnection->exec_TRUNCATEquery($newTable);
            $result = $this->databaseConnection->admin_query('INSERT INTO '.$newTable.' ('.implode(',',
                    $newFields).') SELECT '.implode(',', $fields).' FROM '.$oldTable);
            if ($result) {
//                $this->databaseConnection->admin_query('DROP TABLE '.$oldTable);
                $this->addMessage(\TYPO3\CMS\Core\Messaging\FlashMessage::OK, 'MIGRATED '.strtoupper($title),
                    'MIGRATED '.strtoupper($title).' ENTRIES');
            } else {
                $this->addMessage(\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                    'ERROR ON MIGRATION OF '.strtoupper($title), $this->databaseConnection->sql_error());
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
                /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
                $flashMessage = GeneralUtility::makeInstance(
                    'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                    $messageItem[2],
                    $messageItem[1],
                    $messageItem[0]);

                $this->getFlashMessageService()->getMessageQueueByIdentifier()->addMessage($flashMessage);
            }
        }

        return $this->getFlashMessageService()->getMessageQueueByIdentifier()->renderFlashMessages();
    }

    /**
     * @return \TYPO3\CMS\Core\Messaging\FlashMessageService
     */
    private function getFlashMessageService()
    {
        return $this->objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
    }
}