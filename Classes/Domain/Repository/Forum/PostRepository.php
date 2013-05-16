<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Repository_Forum_PostRepository extends Tx_MmForum_Domain_Repository_AbstractRepository {



	/*
	 * REPOSITORY METHODS
	 */



	/**
	 *
	 * Finds posts for a specific topic. Page navigation is possible.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
	 *                               The topic for which the posts are to be loaded.
	 *
	 * @return Array<Tx_MmForum_Domain_Model_Forum_Post>
	 *                               The selected subset of posts in the specified
	 *                               topic.
	 *
	 */
	public function findForTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$query = $this->createQuery();
		return $query->matching($query->equals('topic', $topic))
			->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))->execute();
	}



	/**
	 *
	 * Finds the last post in a topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
	 *                             The topic for which the last post is to be
	 *                             loaded.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Post
	 *                             The last post of the specified topic.
	 *
	 */
	public function findLastByTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$query = $this->createQuery();
		return $query->matching($query->equals('topic', $topic))
			->setOrderings(Array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))->setLimit(1)
			->execute()->getFirst();
	}



	/**
	 *
	 * Finds the last post in a forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                             The forum for which to load the last post.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Post
	 *                             The last post of the specified forum.
	 *
	 */
	public function findLastByForum(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$query = $this->createQuery();
		return $query->matching($query->equals('topic.forum', $forum))->setOrderings(array('crdate' => 'DESC'))
			->setLimit(1)->execute()->getFirst();
	}



}
