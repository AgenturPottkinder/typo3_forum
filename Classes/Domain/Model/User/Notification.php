<?php
namespace Mittwald\MmForum\Domain\Model\User;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_User
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class Notification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * ATTRIBUTES
	 */

	/**
	 * The execution date of the cron
	 * @var DateTime
	 */
	public $crdate;

	/**
	 * User who is related with this notification
	 * @var \Mittwald\MmForum\Domain\Model\User\FrontendUser
	 */
	public $feuser;


	/**
	 * Post which is related with this notification
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Post
	 */
	public $post;



	/**
	 * Tag which is related with this notification
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Tag
	 */
	public $tag;


	/**
	 * The type of notification (Model Name)
	 * @var string
	 */
	public $type;

	/**
	 * Flag if user already read this notification
	 * @var int
	 */
	public $userRead;


	/**
	 * GETTER
	 */

	/**
	 * Get the date this message has been sent
	 * @return DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Get the type of this notification (Model name)
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the User who is related with this notification
	 * @return \Mittwald\MmForum\Domain\Model\User\FrontendUser
	 */
	public function getFeuser() {
		if ($this->feuser instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->feuser->_loadRealInstance();
		}
		if ($this->feuser === NULL) {
			$this->feuser = new AnonymousFrontendUser();
		}
		return $this->feuser;
	}

	/**
	 * Get the Post which is related with this notification
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post
	 */
	public function getPost() {
		return $this->post;
	}


	/**
	 * Get the tag which is related with this notification
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Tag
	 */
	public function getTag() {
		return $this->tag;
	}


	/**
	 * Get if the user already read this notification
	 * @return int The flag
	 */
	public function getUserRead() {
		return intval($this->userRead);
	}

	/**
	 * SETTER
	 */

	/**
	 * Get the type of this notification (Model Name)
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Sets the user
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $feuser
	 * @return void
	 */
	public function setFeuser(FrontendUser $feuser) {
		$this->feuser = $feuser;
	}


	/**
	 * Sets the post
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $post
	 * @return void
	 */
	public function setPost(\Mittwald\MmForum\Domain\Model\Forum\Post $post) {
		$this->post = $post;
	}


	/**
	 * Set the tag
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Tag $tag
	 * @return void
	 */
	public function setTag(\Mittwald\MmForum\Domain\Model\Forum\Tag $tag) {
		$this->tag = $tag;
	}


	/**
	 * Sets the flag
	 * @param int $userRead
	 * @return void
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}

}