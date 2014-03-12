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
class ReportComment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The comment author
	 * @var \Mittwald\MmForum\Domain\Model\User\FrontendUser
	 */
	protected $author;


	/**
	 * The comment
	 * @var string
	 */
	protected $text;


	/**
	 * The report this comment belongs to.
	 * @var \Mittwald\MmForum\Domain\Model\Moderation\Report
	 */
	protected $report;


	/**
	 * Creation date of this comment
	 * @var DateTime
	 */
	protected $tstamp;





	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Constructor
	 * @param  string                                  $text.
	 */
	public function __construct($text) {
		$this->text   = $text;
	}



	/*
	  * GETTERS
	  */



	/**
	 * Gets the comment author.
	 * @return \Mittwald\MmForum\Domain\Model\User\FrontendUser The comment author.
	 */
	public function getAuthor() {
		if ($this->author instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->author->_loadRealInstance();
		}
		if ($this->author === NULL) {
			$this->author = new \Mittwald\MmForum\Domain\Model\User\AnonymousFrontendUser();
		}
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
	 * @return \Mittwald\MmForum\Domain\Model\Moderation\Report The report.
	 */
	public function getReport() {
		return $this->report;
	}



	/**
	 * Gets this comment's creation timestamp.
	 * @return DateTime The timestamp.
	 */
	public function getTimestamp() {
		return $this->tstamp;
	}



	/*
	 * SETTERS
	 */



	/**
	 * Sets the comment's author.
	 * @param  \Mittwald\MmForum\Domain\Model\User\FrontendUser $author The author.
	 * @return void
	 */
	public function setAuthor(\Mittwald\MmForum\Domain\Model\User\FrontendUser $author) {
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
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\Report $report
	 * @return void
	 */
	public function setReport(Report $report) {
		$this->report = $report;
	}



}
