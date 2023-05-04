<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

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

use Mittwald\Typo3Forum\Domain\Model\Format\BBCode;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Repository\Format\BBCodeRepository;

class BBCodeParserService extends AbstractTextParserService
{
    protected BBCodeRepository $bbCodeRepository;

    /**
     * @var BBCode[]
     */
    protected array $bbCodes = [];
    protected array $userGroupIds = [];

    public function __construct(BBCodeRepository $bbCodeRepository)
    {
        $this->bbCodeRepository = $bbCodeRepository;
    }

    /**
     * Parses the text. Replaces all bb codes in the text with appropriate HTML tags.
     *
     * @param string $text The text that is to be parsed.
     * @param Post $post The post object
     * @return string       The parsed text.
     */
    public function getParsedText(string $text, ?Post $post = null): string
    {
        if (count($this->bbCodes) === 0) {
            $this->bbCodes = $this->bbCodeRepository->findAll()->toArray();
        }
        if ($post !== null) {
            $this->setUserGroupIds($post);
        }
        foreach ($this->bbCodes as $bbCode) {
            /** @var $bbCode \Mittwald\Typo3Forum\Domain\Model\Format\BBCode */
            if ($bbCode->getRegularExpression() === null || $bbCode->getRegularExpressionReplacement() === null) {
                continue;
            }
            if ($post !== null) {
                if ($this->parserAllowedByUser($bbCode)) {
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

    protected function parserAllowedByUser(BBCode $bbCode): bool
    {
        if ($bbCode->getIdsOfGroups() === [] || (count($bbCode->getIdsOfGroups()) === 1 && $bbCode->getIdsOfGroups()[0] === '')) {
            return true;
        }
        foreach ($bbCode->getIdsOfGroups() as $group) {
            if (in_array($group, $this->userGroupIds)) {
                return true;
            }
        }
        return false;
    }

    protected function setUserGroupIds(Post $post): self
    {
        $groups = [];
        if ($post->getAuthor() !== null) {
            foreach ($post->getAuthor()->getUsergroup() as $userGroup) {
                $groups[] = $userGroup->getUid();
            }
        }
        $this->userGroupIds = $groups;

        return $this;
    }
}
