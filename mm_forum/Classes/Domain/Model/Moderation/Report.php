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
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Moderation_Report
	Extends Tx_Extbase_DomainObject_AbstractEntity {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The reported post.
		 * @var Tx_MmForum_Domain_Model_Forum_Post
		 */
	Protected $post;

		/**
		 * The frontend user that created this post.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $reporter;

		/**
		 * The moderator that is assigned to this report.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $moderator;

		/**
		 * The current status of this report.
		 * @var Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
		 */
	Protected $workflowStatus;

		/**
		 * A set of comments that are assigned to this report.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportComment>
		 */
	Protected $comments;





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Creates a new report.
		 *
		 */

	Public Function __construct() {
		$this->comments = New Tx_Extbase_Persistence_ObjectStorage();
	}





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the post
		 * @return Tx_MmForum_Domain_Model_Forum_Post The post
		 *
		 */

	Public Function getPost() { Return $this->post; }



		/**
		 *
		 * Gets the reporter of this report.
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser The reporter
		 *
		 */

	Public Function getReporter() { Return $this->reporter; }



		/**
		 *
		 * Gets the moderator that is assigned to this report.
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser The moderator
		 *
		 */

	Public Function getModerator() { Return $this->moderator; }



		/**
		 *
		 * Gets the current status of this report.
		 * @return Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
		 *                             The current workflow status of this report.
		 *
		 */

	Public Function getWorkflowStatus() { Return $this->workflowStatus; }



		/**
		 *
		 * Gets all comments for this report.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportComment>
		 *                             All comments for this report.
		 *
		 */

	Public Function getComments() { Return $this->comments; }





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the post.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Post $post The post
		 * @return void
		 *
		 */

	Public Function setPost(Tx_MmForum_Domain_Model_Forum_Post $post) { $this->post = $post; }



		/**
		 *
		 * Sets the reporter.
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $reporter The reporter.
		 * @return void
		 */

	Public Function setReporter(Tx_MmForum_Domain_Model_User_FrontendUser $reporter) {
		$this->reporter = $reporter;
	}



		/**
		 *
		 * Sets the moderator.
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $moderator The moderator.
		 * @return void
		 *
		 */

	Public Function setModerator(Tx_MmForum_Domain_Model_User_FrontendUser $moderator) {
		$this->moderator = $moderator;
	}



		/**
		 *
		 * Sets the current workflow status.
		 * @param  Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $workflowStatus
		 *                             The workflow status.
		 * @return void
		 *
		 */

	Public Function setWorkflowStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $workflowStatus) {
		If(!$this->workflowStatus || ($this->workflowStatus && $this->workflowStatus->hasFollowupStatus($workflowStatus)))
			$this->workflowStatus = $workflowStatus;
	}



		/**
		 *
		 * Adds a comment to this report.
		 * @param  Tx_MmForum_Domain_Model_Moderation_ReportComment $comment A comment
		 * @return void
		 *
		 */

	Public Function addComment(Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		$this->comments->attach($comment);
	}



		/**
		 *
		 * Removes a comment from this report.
		 * @param  Tx_MmForum_Domain_Model_Moderation_ReportComment $comment A comment.
		 * @return void
		 *
		 */

	Public Function removeComment(Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		$this->comments->detatch($comment);
	}

}

?>