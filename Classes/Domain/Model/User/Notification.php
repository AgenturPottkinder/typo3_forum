<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

// TODO: Implement notification usage for subscriptions, moderator actions etc.
// TODO: Currently the notification feature is completely unused.
class Notification extends AbstractEntity
{

    /**
     * The execution date of the cron
     * @var \DateTime
     */
    public $crdate;

    /**
     * User who is related with this notification
     * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
     */
    public $feuser;

    /**
     * Post which is related with this notification
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
     */
    public $post;

    /**
     * Tag which is related with this notification
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Tag
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
     * Get the date this notification has been sent
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Get the type of this notification (Model name)
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the type of this notification (Model Name)
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the User who is related with this notification
     * @return FrontendUser
     */
    public function getFeuser()
    {
        if ($this->feuser instanceof LazyLoadingProxy) {
            $this->feuser->_loadRealInstance();
        }
        if ($this->feuser === null) {
            $this->feuser = new AnonymousFrontendUser();
        }

        return $this->feuser;
    }

    /**
     * Sets the user
     *
     * @param FrontendUser $feuser
     */
    public function setFeuser(FrontendUser $feuser)
    {
        $this->feuser = $feuser;
    }

    /**
     * Get the Post which is related with this notification
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Sets the post
     *
     * @param Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the tag which is related with this notification
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set the tag
     *
     * @param Tag $tag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Get if the user already read this notification
     * @return int The flag
     */
    public function getUserRead()
    {
        return (int)$this->userRead;
    }

    /**
     * Sets the flag
     *
     * @param int $userRead
     */
    public function setUserRead($userRead)
    {
        $this->userRead = $userRead;
    }
}
