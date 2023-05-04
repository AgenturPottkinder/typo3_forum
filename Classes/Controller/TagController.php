<?php
namespace Mittwald\Typo3Forum\Controller;

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

use Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException;
use Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ColorRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Annotation\Validate;

class TagController extends AbstractController
{
    protected TagRepository $tagRepository;
    protected TopicRepository $topicRepository;
    protected ColorRepository $colorRepository;

    public function __construct(
        TagRepository $tagRepository,
        TopicRepository $topicRepository,
        ColorRepository $colorRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->topicRepository = $topicRepository;
        $this->colorRepository = $colorRepository;
    }

    /**
     * Listing all tags of this forum.
     */
    public function listAction(int $page = 1): void
    {
        $tags = $this->tagRepository->findAllOrderedByCounter();

        $this->view->assignMultiple([
            'tags' => $tags,
            'page' => $page,
        ]);
    }

    /**
     * Show all topics of a given tag
     * @param Tag $tag
     */
    public function showAction(Tag $tag, int $page = 1): void
    {
        $this->view->assign('tag', $tag);
        $this->view->assign('topics', $this->topicRepository->findByTag($tag));
        $this->view->assign('page', $page);
    }

    /**
     * @throws NotLoggedInException
     *
     * @IgnoreValidation("tag")
     */
    public function newAction(?Tag $tag = null): void
    {
        $user = $this->getCurrentUser();
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in.', 1288084981);
        }
        if (!$user->canCreateTags()) {
            throw new NoAccessException('You cannot create tags.', 1683144970);
        }

        $this->view->assign('tag', $tag ?? GeneralUtility::makeInstance(Tag::class));
        $this->view->assign('colors', $this->colorRepository->findAll());
    }

    /**
     * @Validate("\Mittwald\Typo3Forum\Domain\Validator\Forum\TagValidator", param="tag")
     * @throws NotLoggedInException
     */
    public function createAction(Tag $tag)
    {
        $user = $this->getCurrentUser();
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in.', 1288084981);
        }
        if (!$user->canCreateTags()) {
            throw new NoAccessException('You cannot create tags.', 1683144970);
        }

        $tag->setName(ucwords($tag->getName()));
        $this->tagRepository->add($tag);

        $this->redirect('list');
    }
}
