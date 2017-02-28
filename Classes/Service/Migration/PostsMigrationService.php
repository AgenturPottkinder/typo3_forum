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


class PostsMigrationService extends AbstractMigrationService
{

    public function migrate()
    {
        $this->truncateNewTable();

        if (($posts = $this->databaseConnection->exec_SELECTquery('*', 'tx_mmforum_posts', '1=1'))) {
            foreach ($posts as $post) {
                $text = $this->databaseConnection->exec_SELECTgetSingleRow(
                    '*',
                    'tx_mmforum_posts_text',
                    'post_id = '.$post['uid']
                );

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

                $this->databaseConnection->exec_INSERTquery(
                    'tx_typo3forum_domain_model_forum_post',
                    $newPost
                );
            }

            $this->addMessage(
                \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                'MIGRATED '.$this->getTitle(),
                'MIGRATED '.$this->getTitle().'ENTRIES'
            );
        }

        return $this->generateOutput();
    }

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'tx_typo3forum_domain_model_forum_post';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'POSTS';
    }
}