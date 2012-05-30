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



class Tx_MmForum_Domain_Model_Forum_PostTest extends Tx_MmForum_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Domain_Model_Forum_Post
	 */
	protected $fixture = NULL;



	public function setUp() {
		$this->fixture = new Tx_MmForum_Domain_Model_Forum_Post();
	}



	public function testTimestampIsInitiallyCurrentDate() {
		$this->assertEquals(new DateTime(), $this->fixture->getTimestamp());
	}



	public function testConstructorSetsPostText() {
		$this->fixture = new Tx_MmForum_Domain_Model_Forum_Post('FOO');
		$this->assertEquals('FOO', $this->fixture->getText());
	}



	public function testConstructorSetsPostTextToEmptyStringPerDefault() {
		$this->assertEquals('', $this->fixture->getText());
	}



	public function testSetAuthorSetsAuthor() {
		$this->fixture->setAuthor($user = new Tx_MmForum_Domain_Model_User_FrontendUser('martin'));
		$this->assertTrue($this->fixture->getAuthor() === $user);
	}



	public function testSetTextSetsText() {
		$this->fixture->setText('FOO');
		$this->assertEquals('FOO', $this->fixture->getText());
	}



	public function testSetAttachmentsSetsAttachments() {
		$attachments = new Tx_Extbase_Persistence_ObjectStorage();
		$attachments->attach(new Tx_MmForum_Domain_Model_Forum_Attachment());

		$this->fixture->setAttachments($attachments);
		$this->assertTrue($this->fixture->getAttachments() == $attachments);
	}



	public function testAddAttachmentAddsAttachment() {
		$this->fixture->addAttachment($attachment = new Tx_MmForum_Domain_Model_Forum_Attachment());
		$this->assertContains($attachment, $this->fixture->getAttachments());
	}



	/**
	 * @depends testAddAttachmentAddsAttachment
	 */
	public function testRemoveAttachmentRemovesAttachmet() {
		$this->fixture->addAttachment($attachment = new Tx_MmForum_Domain_Model_Forum_Attachment());
		$this->fixture->removeAttachment($attachment);

		$this->assertEquals(0, count($this->fixture->getAttachments()));
		$this->assertNotContains($attachment, $this->fixture->getAttachments());
	}



	public function testSetTopicSetsTopic() {
		$this->fixture->setTopic($topic = new Tx_MmForum_Domain_Model_Forum_Topic());
		$this->assertTrue($this->fixture->getTopic() === $topic);
	}



	public function testGetAuthorReturnsAnonymousUserIfNoAuthorIsSet() {
		$this->assertInstanceOf('Tx_MmForum_Domain_Model_User_AnonymousFrontendUser', $this->fixture->getAuthor());
	}



	public function testGetAuthorNameReturnsNameOfAnonymousUserIfNoAuthorIsSet() {
		$this->fixture->setAuthorName('martin');
		$this->assertEquals('martin', $this->fixture->getAuthorName());
	}



	public function testGetAuthorNameReturnsNameOfUserIsAuthorIsSet() {
		$this->fixture->setAuthor(new Tx_MmForum_Domain_Model_User_FrontendUser('martin'));
		$this->fixture->setAuthorName('horst');
		$this->assertEquals('martin', $this->fixture->getAuthorName());
	}



	/**
	 * @dataProvider getPostDeleteAndEditAccessRightsCombinations
	 * @param $operation
	 * @param $lastPost
	 * @param $moderator
	 * @param $expectedOutcome
	 */
	public function testDeleteAndEditAccessRightsInDependenceOfModeratorStatusAndPostPositionInTopic($operation,
	                                                                                                 $lastPost,
	                                                                                                 $moderator,
	                                                                                                 $expectedOutcome) {
		// Grant moderation access.
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$forum->expects($this->any())->method('checkModerationAccess')->will($this->returnValue($moderator));
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		// $this->topic->getLastPost() will return another post than $this!
		$topic->expects($this->any())->method('getLastPost')
			->will($this->returnValue($lastPost ? $this->fixture : new Tx_MmForum_Domain_Model_Forum_Post()));
		// Grant delete post access (should fail anyway)
		if (!$moderator) {
			$topic->expects($this->any())->method('checkAccess')
				->with(self::isInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'), $operation)
				->will($this->returnValue(TRUE));
		}
		$topic->expects($this->any())->method('getForum')->will($this->returnValue($forum));

		$user = new Tx_MmForum_Domain_Model_User_FrontendUser('martin');

		$this->fixture->setTopic($topic);
		$this->fixture->setAuthor($user);
		$this->assertEquals($expectedOutcome, $this->fixture->checkAccess($user, $operation));
	}



	public function getPostDeleteAndEditAccessRightsCombinations() {
		return array(array('deletePost', FALSE, FALSE, FALSE), array('deletePost', TRUE, FALSE, TRUE),
		             array('deletePost', FALSE, TRUE, TRUE), array('editPost', FALSE, FALSE, FALSE),
		             array('editPost', TRUE, FALSE, TRUE), array('editPost', FALSE, TRUE, TRUE));
	}



	public function getAccessRightsToBeDelegatedToTopic() {
		return array(array('read', 'moderate', 'administrate'));
	}



	/**
	 * @dataProvider getAccessRightsToBeDelegatedToTopic
	 * @param $operation
	 */
	public function testDelegatesOtherAccessChecksToParentTopic($operation) {
		$user  = new Tx_MmForum_Domain_Model_User_FrontendUser('martin');
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->atLeastOnce())->method('checkAccess')
			->with(self::isInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'), $operation)
			->will($this->returnValue(TRUE));
		$this->fixture->setTopic($topic);
		$this->assertTrue($this->fixture->checkAccess($user, $operation));
	}

}