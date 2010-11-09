<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



	/**
	 *
	 * Repository class for post objects.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Repository_Forum
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Repository_Forum_PostRepository
	Extends Tx_MmForum_Domain_Repository_AbstractRepository {





		/*
		 * CONSTANTS
		 */





		/**
		 * Query for finding the last post in a forum.
		 * @var string
		 */
	Const QUERY_FIND_LAST_BY_FORUM =
		'SELECT p.*
		 FROM        tx_mmforum_domain_model_forum_post p
		        JOIN tx_mmforum_domain_model_topic      t ON t.uid = p.topic
				JOIN tx_mmforum_domain_model_forum      f ON f.uid = t.forum
		 WHERE  f.uid = ? AND p.deleted + t.deleted + f.deleted = 0 AND t.pid IN (###PIDS###) AND f.pid IN (###PIDS###) AND p.pid IN (###PIDS###)
		 ORDER BY p.crdate DESC
		 LIMIT    1';





		/*
		 * REPOSITORY METHODS
		 */





		/**
		 *
		 * Finds posts for a specific topic. Page navigation is possible.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                               The topic for which the posts are to be loaded.
		 * @param  integer $page         The current page
		 * @param  integer $itemsPerPage Number of items on each page.
		 * @return Array<Tx_MmForum_Domain_Model_Forum_Post>
		 *                               The selected subset of posts in the specified
		 *                               topic.
		 *
		 */

	Public Function findForTopic ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                               $page = 1,
	                               $itemsPerPage = 30 ) {
		$query = $this->createQuery();
		Return $query->matching($query->equals('topic', $topic))
			->setOrderings(Array('crdate' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING))
			->setLimit($itemsPerPage)
			->setOffset(($page-1)*$itemsPerPage)
			->execute();
	}



		/**
		 *
		 * Finds the last post in a topic.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic for which the last post is to be
		 *                             loaded.
		 * @return Tx_MmForum_Domain_Model_Forum_Post
		 *                             The last post of the specified topic.
		 *
		 */

	Public Function findLastByTopic ( Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$query = $this->createQuery();
		Return array_pop($query->matching($query->equals('topic', $topic))
			->setOrderings(Array('crdate' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING))
			->setLimit(1)
			->execute());
	}



		/**
		 *
		 * Finds the last post in a forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum for which to load the last post.
		 * @return Tx_MmForum_Domain_Model_Forum_Post
		 *                             The last post of the specified forum.
		 *
		 */

	Public Function findLastByForum ( Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$sql   = $this->getQuery(self::QUERY_FIND_LAST_BY_FORUM);
		$query = $this->createQuery();
		$query->statement($sql, Array($forum->getUid()));
		Return $query->execute();
	}

}

?>
