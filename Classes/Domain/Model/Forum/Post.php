<?php
namespace Mittwald\MmForum\Domain\Model\Forum;


/* *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 * A forum post. Forum posts are submitted to the access control mechanism and can be
 * subscribed by users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Format
 * @version    $Id$
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */
class Post extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	implements \Mittwald\MmForum\Domain\Model\AccessibleInterface, \Mittwald\MmForum\Domain\Model\NotifiableInterface {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The post text.
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $text;



	/**
	 * The rendered post text (contains raw HTML). This attribute has been
	 * implemented for performance reasons. When being rendered (i.e. bb
	 * codes being replaced with corresponding HTML codes, etc.), the
	 * rendered output is cached in this property.
	 *
	 * @var string
	 */
	protected $renderedText;



	/**
	 * The post author.
	 *
	 * @var \Mittwald\MmForum\Domain\Model\User\FrontendUser
	 */
	protected $author;


	/**
	 * The author's username. Necessary for anonymous postings.
	 * @var string
	 * @validate \Mittwald\MmForum\Domain\Validator\Forum\AuthorNameValidator
	 */
	protected $authorName = '';


	/**
	 * The topic.
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Topic
	 */
	protected $topic;



	/**
	 * Creation date.
	 * @var DateTime
	 */
	protected $crdate;


	/**
	 * All subscribers of this forum.
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\MmForum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $supporters;


	/**
	 * Attachments.
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\MmForum\Domain\Model\Forum\Attachment>
	 * @lazy
	 */
	protected $attachments;

	/**
	 * helpfull count
	 * @var integer
	 */
	protected $helpfulCount;

	/*
	  * CONSTRUCTOR
	  */

	/**
	 * Creates a new post.
	 * @param string $text The post text.
	 */
	public function __construct($text = '') {
		$this->attachments = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->supporters = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->crdate      = new DateTime();
		$this->text        = $text;
	}



	/*
	 * GETTERS
	 */

	/**
	 * Gets all users who have subscribed to this forum.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\MmForum\Domain\Model\User\FrontendUser>
	 *                             All subscribers of this forum.
	 */
	public function getSupporters() {
		return $this->supporters;
	}

	/**
	 * Gets the helpful count of this post.
	 * @return integer The helpful count.
	 */
	public function getHelpfulCount() {
		return $this->helpfulCount;
	}

	/**
	 * Gets the text.
	 * @return string The text
	 */
	public function getText() {
		return $this->text;
	}



	/**
	 * Gets the post name. This is just an alias for the topic->getTitle method.
	 * @return The post name.
	 */
	public function getName() {
		return $this->topic->getTitle();
	}



	/**
	 * Alias for getText(). Necessary to implement the NotifiableInterface.
	 * @return string The post text.
	 */
	public function getDescription() {
		return $this->getText();
	}



	/**
	 * Gets the post author.
	 * @return \Mittwald\MmForum\Domain\Model\User\FrontendUser author
	 */
	public function getAuthor() {
		if ($this->author instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->author->_loadRealInstance();
		}
		if ($this->author === NULL) {
			$this->author = new \Mittwald\MmForum\Domain\Model\User\AnonymousFrontendUser();
			if($this->authorName){
				$this->author->setUsername($this->authorName);
			}
		}
		return $this->author;
	}



	/**
	 * Gets the post author's name. Diffentiates between posts created by logged in
	 * users (in this case this user's username is returned) and posts by anonymous
	 * users.
	 * @return string The author's username.
	 */
	public function getAuthorName() {
		if ($this->getAuthor()->isAnonymous()) {
			return $this->authorName;
		} else {
			return $this->getAuthor()->getUsername();
		}
	}



	/**
	 * Gets the topic.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Topic A topic
	 */
	public function getTopic() {
		return $this->topic;
	}



	/**
	 * Gets the forum.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Forum
	 */
	public function getForum() {
		return $this->topic->getForum();
	}



	/**
	 * Gets the post's timestamp.
	 * @return DateTime
	 */
	public function getTimestamp() {
		return $this->crdate;
	}



	/**
	 * Gets the post's attachments.
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\MmForum\Domain\Model\Forum\Attachment>
	 */
	public function getAttachments() {
		return $this->attachments;
	}



	/**
	 * Overrides the isPropertyDirty method. See http://forge.typo3.org/issues/8952
	 * for further information.
	 *
	 * @param mixed $previousValue
	 * @param mixed $currentValue
	 * @return boolean
	 */
	protected function isPropertyDirty($previousValue, $currentValue) {
		if ($currentValue InstanceOf Forum || $currentValue InstanceOf Topic
		) {
			return FALSE;
		} else {
			return parent::isPropertyDirty($previousValue, $currentValue);
		}
	}



	/**
	 * Performs an access check for this post.
	 *
	 * @access private
	 * @param  \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 * @param  string                                    $accessType
	 * @return boolean
	 */
	public function checkAccess(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL, $accessType = 'read') {
		switch ($accessType) {
			case 'editPost':
			case 'deletePost':
				return $this->checkEditOrDeletePostAccess($user, $accessType);
			default:
				return $this->topic->checkAccess($user, $accessType);
		}
	}



	/**
	 * Determines if a user may edit this post. This is only possible if EITHER:
	 * a1.) The user is the author of this post, AND
	 * a2.) This post is the last post in the topic, AND
	 * a3.) The topic generally permits posts to be edited (this would not be the
	 *      case if the topic would e.g. be closed).
	 * OR:
	 * b.)  The current user has moderator access to the forum.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *                             The user for which the authenication is to be
	 *                             checked.
	 * @param                                           $operation
	 * @return boolean             TRUE, if the user is allowed to edit this post,
	 *                             otherwise FALSE.
	 */
	public function checkEditOrDeletePostAccess(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL, $operation) {

		if ($user === NULL || $user->isAnonymous()) {
			return FALSE;
		} else {
			if ($this->getForum()->checkModerationAccess($user)) {
				return TRUE;
			}

			$currentUserIsAuthor   = ($user === $this->getAuthor());
			$postIsLastPostInTopic = ($this === $this->getTopic()->getLastPost());
			$topicGrantsAccess     = $this->getTopic()->checkAccess($user, $operation);

			if ($currentUserIsAuthor && $postIsLastPostInTopic && $topicGrantsAccess) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/*
	 * SETTERS
	 */

	/**
	 * Sets the city value
	 *
	 * @return void
	 * @api
	 */
	public function setHelpfulCount($count) {
		$this->helpfulCount = $count;
	}

	/**
	 * Sets the post author.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $author The post author.
	 * @return void
	 */
	public function setAuthor(\Mittwald\MmForum\Domain\Model\User\FrontendUser $author) {
		if ($author->isAnonymous()) {
			$this->author = NULL;
		} else {
			$this->author = $author;
		}
	}



	/**
	 * Sets the post author's name. Necessary for anonymous postings.
	 * @param $authorName The author's name.
	 */
	public function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}



	/**
	 * Sets the post text.
	 *
	 * @param string $text The post text.
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
		// Reset the rendered text. It will be filled again when the post
		// is rendered.
		$this->renderedText = '';
	}



	/**
	 * Sets the attachments.
	 *
	 * @param  \TYPO3\CMS\Extbase\Persistence\ObjectStorage $attachments The attachments.
	 * @validate $attachments \Mittwald\MmForum\Domain\Validator\Forum\AttachmentValidator
	 * @return void
	 */
	public function setAttachments(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $attachments) {
		$this->attachments = $attachments;
	}



	/**
	 * Adds an or more attachments.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Attachment $attachments The attachment.
	 * @return void
	 */
	public function addAttachments(Attachment $attachments) {
		/* @var Tx_MmForum_Domain_Model_Forum_Attachment */
		$this->attachments->attach($attachments);
	}

	/**
	 * Removes an attachment.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Attachment $attachment The attachment.
	 * @return void
	 */
	public function removeAttachment(Attachment $attachment) {
		if(file_exists($attachment->getAbsoluteFilename())){
			unlink($attachment->getAbsoluteFilename());
		}
		$this->attachments->detach($attachment);
	}

    /**
     * Determines whether this topic has been read by a certain user.
     *
     * @param  \Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter The user who is to be checked.
     * @return boolean                                           TRUE, if the user did read this topic, otherwise FALSE.
     */
    public function hasBeenSupportedByUser(\Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter = NULL) {
        return $supporter ? $this->supporters->contains($supporter) : TRUE;
    }

	/**
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 * @return void
	 */
	public function setTopic(Topic $topic) {
		$this->topic = $topic;
	}

	/**
	 * Marks this topic as read by a certain user.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter The user who read this topic.
	 * @return void
	 */
	public function addSupporter(\Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter) {
		$this->setHelpfulCount($this->getHelpfulCount()+1);
		$this->supporters->attach($supporter);
	}



	/**
	 * Mark this topic as unread for a certain user.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter The user for whom to mark this topic as unread.
	 * @return void
	 */
	public function removeSupporter(\Mittwald\MmForum\Domain\Model\User\FrontendUser $supporter) {
		$this->setHelpfulCount($this->getHelpfulCount()-1);
		$this->supporters->detach($supporter);
	}



	/**
	 * Mark this topic as unread for all users.
	 * @return void
	 */
	public function removeAllSupporters() {
		$this->readers = New \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

}
