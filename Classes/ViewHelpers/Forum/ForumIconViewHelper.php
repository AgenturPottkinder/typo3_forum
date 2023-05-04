<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Forum;

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2016 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper that renders a forum icon.
 */
class ForumIconViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;
    protected FrontendUserRepository $frontendUserRepository;

    public function __construct(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('forum', Forum::class, 'Current forum', true);
    }

    /**
     * render.
     * @return string
     */
    public function render()
    {
        $forum = $this->arguments['forum'];

        $data = $this->getDataArray($forum);

        $cObjectViewHelper = $this->getCObjectViewHelper();
        if ($data['new']) {
            $renderData = [
                'typoscriptObjectPath' => 'plugin.tx_typo3forum.renderer.icons.forum_new',
                'data' => $data
            ];
        } else {
            $renderData = [
                'typoscriptObjectPath' => 'plugin.tx_typo3forum.renderer.icons.forum',
                'data' => $data
            ];
        }

        return $cObjectViewHelper::renderStatic($renderData, function () {
        }, $this->renderingContext);
    }

    /**
     * Generates a data array that will be passed to the typoscript object for
     * rendering the icon.
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum
     *                             The topic for which the icon is to be displayed.
     * @return array               The data array for the typoscript object.
     */
    protected function getDataArray(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum = null)
    {
        if ($forum === null) {
            return [];
        }
        $user = &$this->frontendUserRepository->findCurrent();

        return [
                'new' => !$forum->hasBeenReadByUser($user),
                'closed' => !$forum->checkNewPostAccess($user),
            ];
    }

    /**
     * @return CObjectViewHelper
     */
    protected function getCObjectViewHelper()
    {
        return GeneralUtility::makeInstance(CObjectViewHelper::class);
    }
}
