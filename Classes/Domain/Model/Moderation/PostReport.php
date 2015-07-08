<?php
namespace Mittwald\Typo3Forum\Domain\Model\Moderation;

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

/**
 * Models a post report. Reports are the central object of the moderation
 * component of the typo3_forum extension. Each user can report a forum post
 * to the respective moderator group. In this case, a report object is
 * created.
 *
 * These report objects can be assigned to moderators ans be organized in
 * different workflow stages. Moderators can post comments to each report.
 */
class PostReport extends Report {

	/**
	 * A set of comments that are assigned to this report.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	protected $post;

	/**
	 * Gets the topic to which the reported post belongs to.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Topic The topic.
	 */
	public function getTopic() {
		return $this->post->getTopic();
	}

	/**
	 * Gets the topic to which the reported post belongs to.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post The topic.
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post .
	 *
	 * @return void
	 */
	public function setPost(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		$this->post = $post;
	}

}
