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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper that renders a big button.
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
    protected $settings = null;

    /**
     * initializeArguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('rootline', 'array', 'array of rootline elements', true);
        $this->registerArgument('reverse', 'boolean', '');
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
        $reverse = $this->arguments['reverse'];


        if ($reverse) {
            array_reverse($rootline);
        }

        $class = 'nav nav-pills nav-pills-condensed';
        if ($this->arguments['class']) {
            $class .= ' ' . $this->arguments['class'];
        }
        $this->tag->addAttribute('class', $class);

        $content = '';
        foreach ($rootline as $element) {
            $content .= $this->renderNavigationNode($element);
        }
        $content .= '';

        $this->tag->setContent($content);
        return $this->tag->render();
    }

    /**
     * renderNavigationNode
     *
     * @param $object
     *
     * @return string
     */
    protected function renderNavigationNode($object)
    {
        $extensionName = 'typo3forum';
        $pluginName = 'pi1';
        if ($object instanceof \Mittwald\Typo3Forum\Domain\Model\Forum\Forum) {
            $controller = 'Forum';
            $arguments = ['forum' => $object];
            $icon = 'iconset-22-folder';
        } else {
            $controller = 'Topic';
            $arguments = ['topic' => $object];
            $icon = 'iconset-22-balloon';
        }
        $fullTitle = htmlspecialchars($object->getTitle());
        $limit = (int)$this->settings['cutBreadcrumbOnChar'];
        if ($limit == 0 || strlen($fullTitle) < $limit) {
            $title = $fullTitle;
        } else {
            $title = substr($fullTitle, 0, $limit) . "...";
        }

        $uriBuilder = $this->getUriBuilder();
        $uri = $uriBuilder->reset()->setTargetPageUid((int)$this->settings['pids']['Forum'])
            ->uriFor('show', $arguments, $controller, $extensionName, $pluginName);

        return '<li><a href="' . $uri . '" title="' . $fullTitle . '"><i class="' . $icon . '"></i>' . $title . '</a></li>';
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
