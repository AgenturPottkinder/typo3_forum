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
	 * Repository class for topic objects.
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

Class Tx_MmForum_Domain_Repository_Forum_TopicRepository
	Extends Tx_MmForum_Domain_Repository_AbstractRepository {





		/*
		 * CONSTANTS
		 */





		/**
		 * Query to find topics by post authors.
		 * @var string
		 */
	Const QUERY_FIND_BY_POSTAUTHOR =
		'SELECT t.*
		 FROM        tx_mmforum_domain_model_forum_topic t
		        JOIN tx_mmforum_domain_model_forum_post  p ON t.uid = p.topic
		        JOIN fe_users                            u ON u.uid = p.author
		 WHERE  u.uid=? AND t.deleted + p.deleted = 0 AND t.pid IN (###PIDS###) AND p.pid IN (###PIDS###)
		 GROUP BY t.uid
		 ORDER BY p.crdate DESC';

		/**
		 * Query to count topics by post authors.
		 * @var string
		 */
	Const QUERY_COUNT_BY_POSTAUTHOR =
		'SELECT COUNT(DISTINCT t.uid) AS count
		 FROM        tx_mmforum_domain_model_forum_topic t
		        JOIN tx_mmforum_domain_model_forum_post  p ON t.uid = p.topic
		        JOIN fe_users                            u ON u.uid = p.author
		 WHERE  u.uid=? AND t.deleted + p.deleted = 0 AND t.pid IN (###PIDS###) AND p.pid IN (###PIDS###)';

		/**
		 * Query to find topics for the index view.
		 * @var string
		 */
	Const QUERY_FIND_FOR_INDEX =
		'SELECT t.*
		 FROM        tx_mmforum_domain_model_forum_topic t
		        JOIN tx_mmforum_domain_model_forum_post  p ON p.uid = t.last_post
		 WHERE  t.forum=? AND t.deleted=0 AND t.pid IN (###PIDS###) AND p.pid IN (###PIDS###)
		 ORDER BY t.sticky DESC, p.crdate DESC';





		/*
		 * REPOSITORY METHODS
		 */




		/**
		 *
		 * Finds topics for the forum show view. Page navigation is possible.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                               The forum for which to load the topics.
		 * @param  integer $page         The current page
		 * @param  integer $itemsPerPage The number of items on each page.
		 * @return Array<Tx_MmForum_Domain_Model_Forum_Topic>
		 *                               The selected subset of topics.
		 *
		 */

	Public Function findForIndex(Tx_MmForum_Domain_Model_Forum_Forum $forum, $page=1, $itemsPerPage=30) {
		$sql = $this->getPaginatedQuery(self::QUERY_FIND_FOR_INDEX, $page, $itemsPerPage);
		$query = $this->createQuery();
		$query->statement ( $sql, array ( $forum->getUid() ));
		Return $query->execute();
	}



		/**
		 *
		 * Finds topics by post authors, i.e. all topics that contain at least one post
		 * by a specific author. Page navigation is possible.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                               The frontend user whose topics are to be loaded.
		 * @param  integer $page         The current page
		 * @param  integer $itemsPerPage The number of items on each page.
		 * @return Array<Tx_MmForum_Domain_Model_Forum_Topic>
		 *                               All topics that contain a post by the specified
		 *                               user.
		 *
		 */

	Public Function findByPostAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user, $page=1, $itemsPerPage=30) {
		$sql = $this->getPaginatedQuery(self::QUERY_FIND_BY_POSTAUTHOR, $page, $itemsPerPage);
		$query = $this->createQuery();
		$query->statement ( $sql , array ( $user->getUid() ));
		Return $query->execute();
	}



		/**
		 *
		 * Counts topics by post authors. See findByPostAuthor.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                               The frontend user whose topics are to be loaded.
		 * @return integer               The number of topics that contain a post by the
		 *                               specified user.
		 *
		 */

	Public Function countByPostAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$sql = $this->getQuery(self::QUERY_COUNT_BY_POSTAUTHOR, $page, $itemsPerPage);
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement ( $sql , array ( $user->getUid() ));
		$r = $query->execute();
		Return $r[0]['count'];
	}

	
	
		/**
		 *
		 * Counts all topics for the forum show view.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum for which the topics are to be counted.
		 * @return integer             The topic count.
		 *
		 */

	Public Function countForIndex(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		Return $this->countByForum($forum);
	}

}
?>