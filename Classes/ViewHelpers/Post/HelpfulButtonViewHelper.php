<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Post;

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

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;

class HelpfulButtonViewHelper extends CObjectViewHelper
{

    /**
     * @var array
     */
    protected $settings = null;

    /**
     * The frontend user repository.
     * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
     */
    protected $frontendUserRepository = null;

    /**
     * An authentication service. Handles the authentication mechanism.
     *
     * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
     * @inject
     */
    protected $authenticationService;

    public function initialize()
    {
        parent::initialize();
        $this->settings = $this->templateVariableContainer->get('settings');
    }

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('class', 'string', 'CSS class');
        $this->registerArgument('post', Post::class, 'Post');
        $this->registerArgument('countTarget', 'string', 'countTarget');
        $this->registerArgument('countUserTarget', 'string', 'countUserTarget');
        $this->registerArgument('title', 'string', 'title');
    }

    /**
     * @return string
     */
    public function render()
    {
        $class = $this->settings['forum']['post']['helpfulBtn']['iconClass'];

        /* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post */
        $post = $this->arguments['post'];
        $title = $this->arguments['title'];
        $countUserTarget = $this->arguments['countUserTarget'];
        $countTarget = $this->arguments['countTarget'];

        if ($this->hasArgument('class')) {
            $class .= ' ' . $this->arguments['class'];
        }
        if ($post->getAuthor()->getUid() != $this->authenticationService->getUser()->getUid() && !$this->authenticationService->getUser()->isAnonymous()) {
            $class .= ' tx-typo3forum-helpfull-btn';
        }

        if ($post->hasBeenSupportedByUser($this->authenticationService->getUser())) {
            $class .= ' supported';
        }
        $btn = '<div data-toogle="tooltip" title="' . $title . '" class="' . $class . '" data-countusertarget="' . $countUserTarget . '" data-counttarget="' . $countTarget . '" data-post="' . $post->getUid() . '" data-pageuid="' . $this->settings['pids']['Forum'] . '" data-eid="' . $this->settings['forum']['post']['helpfulBtn']['eID'] . '"></div>';
        return $btn;
    }
}
