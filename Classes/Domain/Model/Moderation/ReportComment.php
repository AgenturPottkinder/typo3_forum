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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A report comment. Each moderation report consists of a set -- and at least one --
 * of these comments.
 */
class ReportComment extends AbstractEntity
{

    /**
     * The comment author
     * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
     */
    protected $author;

    /**
     * The comment
     * @var string
     */
    protected $text;

    /**
     * The report this comment belongs to.
     * @var \Mittwald\Typo3Forum\Domain\Model\Moderation\Report
     */
    protected $report;

    /**
     * Creation date of this comment
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * Constructor
     *
     * @param string $text .
     */
    public function __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Gets the comment author.
     * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The comment author.
     */
    public function getAuthor()
    {
        if ($this->author instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
            $this->author->_loadRealInstance();
        }
        if ($this->author === null) {
            $this->author = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
        }

        return $this->author;
    }

    /**
     * Sets the comment's author.
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $author The author.
     */
    public function setAuthor(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $author)
    {
        $this->author = $author;
    }

    /**
     * Gets the comment text.
     * @return string The comment text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the comment text.
     *
     * @param string $text The comment text.
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Gets the parent report.
     * @return \Mittwald\Typo3Forum\Domain\Model\Moderation\Report The report.
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Sets the comment's report.
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report
     */
    public function setReport(\Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report)
    {
        $this->report = $report;
    }

    /**
     * Gets this comment's creation timestamp.
     * @return \DateTime The timestamp.
     */
    public function getTimestamp()
    {
        return $this->tstamp;
    }
}
