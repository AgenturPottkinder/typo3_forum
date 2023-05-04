<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Utility\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

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

class QuoteParserService extends AbstractTextParserService
{
    protected PostRepository $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * Renders the parsed text.
     */
    public function getParsedText(string $text, ?Post $post = null): string
    {
        do {
            $text = preg_replace_callback('/\[quote](.*?)\[\/quote\]\w*/is', [$this, 'replaceSingleCallback'], $text, -1, $c);
        } while ($c > 0);
        do {
            $text = preg_replace_callback('/\[quote=([0-9]+)\](.*?)\[\/quote\]\w*/is', [$this, 'replaceCallback'], $text, -1, $c);
        } while ($c > 0);
        return $text;
    }

    /**
     * Callback function for rendering quotes.
     */
    protected function replaceSingleCallback(array $matches): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setControllerContext($this->controllerContext);
        $view->setTemplatePathAndFilename(File::replaceSiteRelPath($this->settings['template']));
        $view->assign('quote', trim($matches[1]));
        $view->assign('post', null);
        return $view->render();
    }

    /**
     * Callback function for rendering quotes.
     */
    protected function replaceCallback(array $matches): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setControllerContext($this->controllerContext);
        $view->setTemplatePathAndFilename(File::replaceSiteRelPath($this->settings['template']));

        $tmp = $this->postRepository->findByUid((int)$matches[1]);
        if (!empty($tmp)) {
            $view->assign('post', $tmp);
        }

        $view->assign('quote', trim($matches[2]));
        return $view->render();
    }
}
