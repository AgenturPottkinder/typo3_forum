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

/**
 * Class PostsMigrationService
 * @package Mittwald\Typo3Forum\Service\Migration
 */
class PostsMigrationService extends AbstractMigrationService
{

    /**
     * @return string
     */
    public function migrate()
    {
        $this->truncateNewTable();

        if (($posts = $this->getOldPosts())) {
            foreach ($posts as $post) {
                if (($text = $this->getTextForCurrentPost($post['uid']))) {
                    // Preserves the tx_mmforum_posts.uid instead of tx_mmforum_posts_text.uid
                    unset($text['uid']);
                    $newPost = $this->getNewPost(array_merge($post, $text));
                    $this->persistNewPost($newPost);
                }
            }

            $this->addMessage(
                FlashMessage::OK,
                'MIGRATED ' . $this->getTitle(),
                'MIGRATED ' . $this->getTitle() . ' ENTRIES'
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
        return 'tx_mmforum_posts';
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

    /**
     * @return bool|\mysqli_result|object
     */
    private function getOldPosts()
    {
        return $this->databaseConnection->exec_SELECTquery('*', $this->getOldTableName(), '1=1');
    }

    /**
     * @param array $newPost
     */
    private function persistNewPost(array $newPost)
    {
        $this->databaseConnection->exec_INSERTquery($this->getNewTableName(), $newPost);
    }

    /**
     * @param $postId
     * @return array|FALSE|NULL
     */
    private function getTextForCurrentPost($postId)
    {
        return $this->databaseConnection->exec_SELECTgetSingleRow(
            '*',
            'tx_mmforum_posts_text',
            'post_id = ' . $postId
        );
    }

    /**
     * @param $oldData
     * @return array
     */
    private function getNewPost($oldData)
    {
        return [
            'uid' => $oldData['uid'],
            'pid' => $oldData['pid'],
            'topic' => $oldData['topic_id'],
            'text' => $oldData['post_text'],
            'rendered_text' => $oldData['cache_text'],
            'author' => $oldData['poster_id'],
            'tstamp' => $oldData['tstamp'],
            'crdate' => $oldData['crdate'],
            'deleted' => $oldData['deleted'],
            'hidden' => $oldData['hidden'],
            'attachments' => (int) $oldData['attachment'],
            'l18n_diffsource' => ''
        ];
    }

}