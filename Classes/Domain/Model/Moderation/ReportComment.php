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
 * A report comment. Each moderation report consists of a set -- and at least one --
 * of these comments.
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
class Tx_MmForum_Domain_Model_Moderation_ReportComment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The comment author
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	protected $author;


	/**
	 * The comment
	 * @var string
	 * @validate NotEmpty
	 */
	protected $text;


	/**
	 * The report this comment belongs to.
	 * @var Tx_MmForum_Domain_Model_Moderation_Report
	 */
	protected $report;


	/**
	 * This comment's creation timestamp.
	 * @var DateTime
	 */
	protected $crdate = NULL;



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Constructor
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $author
	 * @param                                           $text.
	 */
	public function __construct(Tx_MmForum_Domain_Model_User_FrontendUser $author, $text) {
		$this->author = $author;
		$this->text   = $text;
	}



	/*
	  * GETTERS
	  */



	/**
	 * Gets the comment author.
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser The comment author.
	 */
	public function getAuthor() {
		return $this->author;
	}



	/**
	 * Gets the comment text.
	 * @return string The comment text.
	 */
	public function getText() {
		return $this->text;
	}



	/**
	 * Gets the parent report.
	 * @return Tx_MmForum_Domain_Model_Moderation_Report The report.
	 */
	public function getReport() {
		return $this->report;
	}



	/**
	 * Gets this comment's creation timestamp.
	 * @return DateTime The timestamp.
	 */
	public function getTimestamp() {
		return $this->crdate;
	}



	/*
	 * SETTERS
	 */



	/**
	 * Sets the comment's author.
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $author The author.
	 * @return void
	 */
	public function setAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $author) {
		$this->author = $author;
	}



	/**
	 * Sets the comment text.
	 * @param string $text The comment text.
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}



	/**
	 * Sets the comment's report.
	 *
	 * @param Tx_MmForum_Domain_Model_Moderation_Report $report
	 * @return void
	 */
	public function setReport(Tx_MmForum_Domain_Model_Moderation_Report $report) {
		$this->report = $report;
	}



}
