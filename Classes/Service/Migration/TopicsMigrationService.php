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

class TopicsMigrationService extends AbstractMigrationService
{

    protected function migrateTable(
        $oldTable,
        $newTable,
        array $newFields,
        array $fields,
        $title
    ) {
        if (($oldTopics = $this->getOldData())) {
            foreach ($oldTopics as $oldTopic) {
                $this->createNewData($oldTopic);
            }
            $this->addMessage(
                FlashMessage::OK, 'MIGRATED ' . $this->getTitle(), 'MIGRATED ' . $this->getTitle() . ' ENTITIES'
            );
        }
    }

    /**
     * @param array $data
     */
    protected function createNewData(array $data)
    {
        if (($fields = array_intersect_key($data, $this->getFieldsDefinition()))) {
            $updateFields = [];

            foreach ($this->getFieldsDefinition() as $oldKey => $newKey) {
                $updateFields[$newKey] = $fields[$oldKey];
            }

            $updateFields['last_post_crdate'] = $this->getCrdateOfPost($updateFields['last_post']);
            $updateFields['post_count'] += 1;

            $this->databaseConnection->exec_INSERTquery($this->getNewTableName(), $updateFields);
        }

    }


    /**
     * @return bool|\mysqli_result|object
     */
    protected function getOldData()
    {
        return $this->databaseConnection->exec_SELECTquery('*', $this->getOldTableName(), '1=1');
    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [
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
            'cruser_id' => 'l18n_diffsource',
            'topic_replies' => 'post_count',
        ];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'tx_mmforum_topics';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'tx_typo3forum_domain_model_forum_topic';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'TOPICS';
    }

    /**
     * @param $postId
     * @return int
     */
    private function getCrdateOfPost($postId)
    {
        $row = $this->databaseConnection->exec_SELECTgetSingleRow(
            'crdate',
            'tx_mmforum_posts',
            'uid = ' . $postId
        );

        return (int)$row['crdate'];
    }
}