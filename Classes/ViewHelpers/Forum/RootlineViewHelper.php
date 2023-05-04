<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Forum;

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

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper that renders a rootline.
 */
class RootlineViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'ul';

    /**
     * @var array
     */
    protected $settings;

    /**
     * initializeArguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('ulClass', 'string', 'CSS class for rootline container', false, 'breadcrumb');
        $this->registerArgument('liClass', 'string', 'CSS class for each rootline element', false, 'breadcrumb-item');
        $this->registerArgument('rootline', 'array', 'Array of rootline elements', true);
        $this->registerArgument('reverse', 'boolean', 'Reverse the order of the elements in the rootline');
        $this->registerArgument('forumicon', 'string', 'Class to use for the forum icon');
        $this->registerArgument('topicicon', 'string', 'Class to use for the topic icon');
    }

    /**
     * initialize.
     */
    public function initialize()
    {
        parent::initialize();
        $this->settings = $this->templateVariableContainer->get('settings');
    }

    /**
     * render.
     * @return string
     */
    public function render()
    {
        $rootline = $this->arguments['rootline'];

        if ($this->arguments['reverse']) {
            $rootline = array_reverse($rootline);
            $currentNodeIndex = 0;
        } else {
            $currentNodeIndex = count($rootline) - 1;
        }

        $class = '';
        if ($this->hasArgument('ulClass')) {
            $class = $this->arguments['ulClass'];
        }
        $this->tag->addAttribute('class', $class);

        $content = '';
        foreach ($rootline as $index => $element) {
            $content .= $this->renderNavigationNode($element, $index === $currentNodeIndex);
        }

        $this->tag->setContent($content);
        return $this->tag->render();
    }

    /**
     * renderNavigationNode
     *
     * @param $object
     *
     * @param bool $isCurrentNode
     * @return string
     */
    protected function renderNavigationNode($object, bool $isCurrentNode)
    {
        $extensionName = 'typo3forum';
        $pluginName = 'forum';
        if ($object instanceof \Mittwald\Typo3Forum\Domain\Model\Forum\Forum) {
            $controller = 'Forum';
            $arguments = ['forum' => $object];
            $icon = $this->arguments['forumicon'];
        } else {
            $controller = 'Topic';
            $arguments = ['topic' => $object];
            $icon = $this->arguments['topicicon'];
        }
        $fullTitle = htmlspecialchars($object->getTitle());
        $limit = (int)$this->settings['cutBreadcrumbOnChar'];
        if ($limit == 0 || strlen($fullTitle) < $limit) {
            $title = $fullTitle;
        } else {
            $title = substr($fullTitle, 0, $limit) . '...';
        }

        $uriBuilder = $this->getUriBuilder();
        $uri = $uriBuilder->reset()->setTargetPageUid((int)$this->settings['pids']['Forum'])
            ->uriFor('show', $arguments, $controller, $extensionName, $pluginName);

        $liClass = '';
        if ($this->hasArgument('liClass')) {
            $liClass = $this->arguments['liClass'];
        }

        $icon = empty($icon) ? '' : '<i class="' . $icon . '"></i>';

        return '<li class="' . $liClass . '"><a href="' . $uri . '" title="' . $fullTitle . '">' . $icon . $title . '</a></li>';
    }

    /**
     * getUriBuilder.
     * @return UriBuilder
     */
    private function getUriBuilder()
    {
        return $this->renderingContext->getControllerContext()->getUriBuilder();
    }
}
