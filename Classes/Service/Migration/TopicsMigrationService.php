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


class TopicsMigrationService extends AbstractMigrationService
{

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
            'cruser_id' => 'l18n_diffsource'
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
}