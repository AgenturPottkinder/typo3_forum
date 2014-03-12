<?php
namespace Mittwald\MmForum\Domain\Model\Moderation;


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
 * Models a post report. Reports are the central object of the moderation
 * component of the mm_forum extension. Each user can report a forum post
 * to the respective moderator group. In this case, a report object is
 * created.
 *
 * These report objects can be assigned to moderators ans be organized in
 * different workflow stages. Moderators can post comments to each report.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Moderation
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class PostReport extends Report {

	/**
	 * A set of comments that are assigned to this report.
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Post
	 */
	protected $post;


	/**
	 * Gets the topic to which the reported post belongs to.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Topic The topic.
	 */
	public function getTopic() {
		return $this->post->getTopic();
	}

	/**
	 * Gets the topic to which the reported post belongs to.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post The topic.
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $post.
	 * @return voidc.
	 */
	public function setPost(\Mittwald\MmForum\Domain\Model\Forum\Post $post) {
		$this->post = $post;
	}

}
