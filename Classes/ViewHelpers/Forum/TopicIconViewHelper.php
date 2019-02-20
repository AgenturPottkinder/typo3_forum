<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Forum;

/* *
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

use Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderableClosure;

/**
 * ViewHelper that renders a topic icon.
 */
class TopicIconViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * The frontend user repository.
     * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository = null;

    /**
     *
     * Initializes the view helper arguments.
     * @return void
     *
     */
    public function initializeArguments()
    {
        $this->registerArgument('important', 'integer',
            'Amount of posts required for a topic to contain in order to be marked as important', false, 15);
        $this->registerArgument('topic', Topic::class, 'Current topic', true);
        $this->registerArgument('width', 'integer', 'width of icon');
    }

    /**
     *
     * Renders the topic icon.
     *
     * @return string The rendered icon.
     */
    public function render()
    {

        $topic = $this->arguments['topic'];
        $width = $this->arguments['width'];

        $data = $this->getDataArray($topic);

        $cObjectViewHelper = $this->getCObjectViewHelper();

        if ($data['new']) {
            $renderData = [
                'typoscriptObjectPath' => 'plugin.tx_typo3forum.renderer.icons.topic_new',
                'data' => $data
            ];
        } else {
            $renderData = [
                'typoscriptObjectPath' => 'plugin.tx_typo3forum.renderer.icons.topic',
                'data' => $data
            ];
        }

        return $cObjectViewHelper::renderStatic($renderData, function () {}, $this->renderingContext);
    }

    /**
     *
     * Generates a data array that will be passed to the typoscript object for
     * rendering the icon.
     * @param Topic $topic The topic for which the icon is to be displayed.
     * @return array The data array for the typoscript object.
     *
     */
    protected function getDataArray(Topic $topic = null)
    {
        if ($topic === null) {
            return [];
        } elseif ($topic instanceof ShadowTopic) {
            return ['moved' => true];
        } else {
            $isImportant = $topic->getPostCount() >= $this->arguments['important'];

            return [
                'important' => $isImportant,
                'new' => !$topic->hasBeenReadByUser($this->frontendUserRepository->findCurrent()),
                'closed' => $topic->isClosed(),
                'sticky' => $topic->isSticky(),
                'solved' => $topic->getIsSolved(),
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
