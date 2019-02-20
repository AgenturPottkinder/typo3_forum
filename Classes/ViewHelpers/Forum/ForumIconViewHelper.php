<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Forum;

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

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderableClosure;

/**
 * ViewHelper that renders a forum icon.
 */
class ForumIconViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * The frontend user repository.
     * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository = null;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('forum', Forum::class, 'Current forum', true);
        $this->registerArgument('width', 'integer', 'icon width', false, 0);
        $this->registerArgument('alt', 'string', 'icon alt text', false, '');
    }

    /**
     * render.
     * @return string
     */
    public function render()
    {

        $forum = $this->arguments['forum'];
        $width = $this->arguments['width'];
        $alt = $this->arguments['alt'];

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

        return $cObjectViewHelper::renderStatic($renderData, function () {}, $this->renderingContext);
    }

    /**
     *
     * Generates a data array that will be passed to the typoscript object for
     * rendering the icon.
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum
     *                             The topic for which the icon is to be displayed.
     * @return array               The data array for the typoscript object.
     *
     */
    protected function getDataArray(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum = null)
    {
        if ($forum === null) {
            return [];
        } else {
            $user = &$this->frontendUserRepository->findCurrent();

            return [
                'new' => !$forum->hasBeenReadByUser($user),
                'closed' => !$forum->checkNewPostAccess($user),
            ];
        }
    }

    /**
     * @return CObjectViewHelper
     */
    protected function getCObjectViewHelper()
    {
        return GeneralUtility::makeInstance(CObjectViewHelper::class);
    }
}
