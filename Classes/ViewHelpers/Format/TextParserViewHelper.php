<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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



/**
 *
 * ViewHelper that performs text parsing operations on text input.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Format
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ViewHelpers_Format_TextParserViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {



	/**
	 * The text parser service
	 * @var Tx_MmForum_TextParser_TextParserService
	 */
	protected $textParserService;


	/**
	 * An instance of the post repository class. The repository is needed
	 * only when a rendered post text has to be persisted in the database.
	 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository;



	/**
	 *
	 * Injects an instance of the text parser service.
	 * @param  Tx_MmForum_TextParser_TextParserService $textParserService
	 *                             An instance of the text parser service.
	 * @return void
	 *
	 */
	public function injectTextParserService(Tx_MmForum_TextParser_TextParserService $textParserService) {
		$this->textParserService = $textParserService;
	}



	/**
	 *
	 * Injects an instance of the post repository class.
	 * @param  Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository
	 *                             An instance of the post repository class.
	 * @return void
	 *
	 */
	public function injectPostRepository(Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository) {
		$this->postRepository = $postRepository;
	}



	/**
	 *
	 * Renders the input text.
	 *
	 * @param  string                             $configuration The configuration path
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $post
	 * @param  string                             $content       The content to be rendered. If NULL, the node
	 *                                                           content will be rendered instead.
	 * @return string                The rendered text
	 *
	 */
	public function render($configuration = 'plugin.tx_mmforum.settings.textParsing',
	                       Tx_MmForum_Domain_Model_Forum_Post $post = NULL, $content = NULL) {
		$this->textParserService->setControllerContext($this->controllerContext);
		$this->textParserService->loadConfiguration($configuration);

		if ($post !== NULL) {
			#if(!$post->_getProperty('renderedText')) {
			$renderedText = $this->textParserService->parseText($post->getText());
			#	$post->_setProperty('renderedText', $renderedText);
			#	$this->postRepository->update($post);
			#} else $renderedText = $post->_getProperty('renderedText');
		} else {
			$renderedText = $this->textParserService->parseText($content ? $content : trim($this->renderChildren()));
		}

		return $renderedText;
	}



}
