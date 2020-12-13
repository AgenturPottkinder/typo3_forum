<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

use Mittwald\Typo3Forum\Domain\Model\Format\BBCode;
use Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode;

/*                                                                      *
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

use Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use TYPO3\CMS\Extbase\Annotation\Inject;

class BBCodeParserService extends AbstractTextParserService
{

    /**
     * @var \Mittwald\Typo3Forum\Domain\Repository\Format\BBCodeRepository
     * @Inject
     */
    protected $bbCodeRepository;

    /**
     * All bb codes.
     * @var array<\Mittwald\Typo3Forum\Domain\Model\Format\BBCode>
     */
    protected $bbCodes;

    /**
     * List of Ids of the current UserGroup
     * @var array
     */
    protected $userGroupIds;

    /**
     * Parses the text. Replaces all bb codes in the text with appropriate HTML tags.
     *
     * @param string $text The text that is to be parsed.
     * @param Post $post The post object
     * @return string       The parsed text.
     */
    public function getParsedText($text, $post = null) : string
    {
        if ($this->bbCodes === null) {
            $this->bbCodes = $this->bbCodeRepository->findAll();
        }
        if($post !== null) {
            $this->setuserGroupIds($post);
        }
        foreach ($this->bbCodes as $bbCode) {
            /** @var $bbCode \Mittwald\Typo3Forum\Domain\Model\Format\BBCode */
            if ($bbCode instanceof QuoteBBCode || $bbCode instanceof ListBBCode) {
                continue;
            }
            if($post !== null) {
                if($this->parserAllowedByUser($bbCode)) {
                    $text = preg_replace($bbCode->getRegularExpression(), $bbCode->getRegularExpressionReplacement(), $text);
                } else {
                    $text = preg_replace($bbCode->getRegularExpression(), $bbCode->getRegularExpressionReplacementBlocked(), $text);
                }
            } else {
                $text = preg_replace($bbCode->getRegularExpression(), $bbCode->getRegularExpressionReplacement(), $text);
            }
        }
        return $text;
    }

    protected function parserAllowedByUser(BBCode $bbCode) : bool
    {
        if($bbCode->getIdsOfGroups() === []) {
            return true;
        }
        foreach($bbCode->getIdsOfGroups() as $group) {
            if(in_array($group, $this->userGroupIds)) {
                return true;
            }
        }
        return false;
    }

    protected function setuserGroupIds(Post $post) : void
    {
        $groups = [];
        if($post->getAuthor() !== null) {
            foreach ($post->getAuthor()->getUsergroup() as $userGroup) {
                $groups[] = $userGroup->getUid();
            }
        }
        $this->userGroupIds = $groups;
    }
}
