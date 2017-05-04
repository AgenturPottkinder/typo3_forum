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


use TYPO3\CMS\Core\Utility\GeneralUtility;

class ForumMigrationService extends AbstractMigrationService
{

    /**
     * @return string
     */
    public function migrate()
    {

        if (($result = $this->selectEntities($this->getOldTableName()))) {
            foreach ($result as $row) {
                $this->createForumRelations($row);
            }
        }

        $output = parent::migrate();

        $this->createRootForums();

        return $output;
    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [
            'uid' => 'uid',
            'pid' => 'pid',
            'forum_name' => 'title',
            'forum_desc' => 'description',
            'forum_topics' => 'topic_count',
            'forum_posts' => 'post_count',
            'forum_last_post_id' => 'last_post',
            'parentID' => 'forum',
            'sorting' => 'sorting',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'hidden' => 'hidden',
            'cruser_id' => 'l18n_diffsource',
        ];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'tx_mmforum_forums';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'tx_typo3forum_domain_model_forum_forum';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'FORUMS';
    }

    /**
     *
     */
    protected function createRootForums()
    {
        if (($result = $this->databaseConnection->exec_SELECTquery(
            'COUNT(*) AS count, pid', $this->getNewTableName(), 'forum = 0', 'pid'
        ))
        ) {
            foreach ($result as $row) {
                if (($row['count'] > 1)) {
                    $this->createRootForum($row['pid']);
                }
            }
        }
    }

    /**
     * @param $pid
     */
    protected function createRootForum($pid)
    {
        $fields = [
            'pid' => $pid,
            'title' => 'Forum',
            'l18n_diffsource' => '',
        ];

        $this->databaseConnection->exec_INSERTquery($this->getNewTableName(), $fields);
        $rootForumId = $this->databaseConnection->sql_insert_id();
        $updateFields = [
            'forum' => $rootForumId,
        ];

        $this->databaseConnection->exec_UPDATEquery(
            $this->getNewTableName(), 'pid = ' . $pid . ' AND forum = 0 AND uid <>' . $rootForumId, $updateFields
        );
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
        if (($row[$role . 'rights_' . $operation])) {
            $groups = GeneralUtility::trimExplode(',', $row[$role . 'rights_' . $operation]);
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
        $acl['l18n_diffsource'] = '';
        $acl['affected_group'] = (int)$acl['affected_group'];
        $this->databaseConnection->exec_INSERTquery('tx_typo3forum_domain_model_forum_access', $acl);
    }

    /**
     * @param $table
     * @return bool|\mysqli_result|object
     */
    private function selectEntities($table)
    {
        return $this->databaseConnection->exec_SELECTquery('*', $table, '1=1', '', 'parentID ASC');
    }
}