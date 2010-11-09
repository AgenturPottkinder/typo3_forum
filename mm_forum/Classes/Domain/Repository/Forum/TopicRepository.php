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
 * Repository for Tx_MmForum_Domain_Model_Forum_Forum
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
Class Tx_MmForum_Domain_Repository_Forum_TopicRepository
	Extends Tx_MmForum_Domain_Repository_AbstractRepository {



		/*
		 * CONSTANTS
		 */

	Const QUERY_FIND_BY_POSTAUTHOR =
		'SELECT t.*
		 FROM        tx_mmforum_domain_model_forum_topic t
		        JOIN tx_mmforum_domain_model_forum_post  p ON t.uid = p.topic
		        JOIN fe_users                            u ON u.uid = p.author
		 WHERE  u.uid=? AND t.deleted + p.deleted = 0 AND t.pid IN (%1$s) AND p.pid IN (%1$s)
		 GROUP BY t.uid';

	Const QUERY_FIND_FOR_INDEX =
		'SELECT t.*
		 FROM        tx_mmforum_domain_model_forum_topic t
		        JOIN tx_mmforum_domain_model_forum_post  p ON p.uid = t.last_post
		 WHERE  t.forum=? AND t.deleted=0 AND t.pid IN (%1$s) AND p.pid IN (%1$s)
		 ORDER BY t.sticky DESC, p.crdate DESC
		 LIMIT  %2$d,%3$d';



		/*
		 * REPOSITORY METHODS
		 */

	Public Function findForIndex(Tx_MmForum_Domain_Model_Forum_Forum $forum, $page=1, $itemsPerPage=30) {
		$sql = sprintf(self::QUERY_FIND_FOR_INDEX, implode(',',$this->getPidList()), intval(($page-1)*$itemsPerPage), intval($itemsPerPage));
		$query = $this->createQuery();
		$query->statement ( $sql, array ( $forum->getUid() ));
		Return $query->execute();
	}

	Public Function findByPostAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$sql = sprintf(self::QUERY_FIND_BY_POSTAUTHOR, implode(',',$this->getPidList()) );
		$query = $this->createQuery();
		$query->statement ( self::QUERY_FIND_BY_POSTAUTHOR, array ( $user->getUid() ));
		Return $query->execute();

	}

}
?>