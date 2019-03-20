<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Format;

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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper that performs text parsing operations on text input.
 */
class TextParserViewHelper extends AbstractViewHelper
{

    /**
     * The text parser service
     * @var \Mittwald\Typo3Forum\TextParser\TextParserService
     * @inject
     */
    protected $textParserService;

    /**
     * An instance of the post repository class. The repository is needed
     * only when a rendered post text has to be persisted in the database.
     * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
     * @inject
     */
    protected $postRepository;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('configuration', 'string', 'The configuration path', false, 'plugin.tx_typo3forum.settings.textParsing');
        $this->registerArgument('post', Post::class, '', false, null);
        $this->registerArgument('content', 'string', 'The content to be rendered. If NULL, the node content will be rendered instead.', false, null);
    }

    /**
     * render.
     * @return string
     * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
     */
    public function render()
    {
        $this->textParserService->setControllerContext($this->renderingContext->getControllerContext());
        $this->textParserService->loadConfiguration($this->arguments['configuration']);

        /* @var Post $post */
        $post = $this->arguments['post'];
        if ($post !== null) {
            $renderedText = $this->textParserService->parseText($post->getText());
        } else {
            $renderedText = $this->textParserService->parseText($this->arguments['content'] ?: trim($this->renderChildren()));
        }

        return $renderedText;
    }
}
