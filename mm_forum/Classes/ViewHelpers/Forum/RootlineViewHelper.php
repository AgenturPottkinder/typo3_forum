<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
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
 * ViewHelper that renders a big button.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Control
 * @version    $Id: BigButtonViewHelper.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ViewHelpers_Forum_RootlineViewHelper
		extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper
{



	/**
	 * @var string
	 */
	protected $tagName = 'ul';


	/**
	 * @var array
	 */
	protected $settings = NULL;



	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
	}



	public function initialize()
	{
		parent::initialize();
		$this->settings = $this->templateVariableContainer->get('settings');
	}



	/**
	 *
	 * @param array $rootline
	 * @param boolean $reverse
	 *
	 */
	public function render(array $rootline, $reverse=FALSE)
	{
		if ($reverse)
		{
			array_reverse($rootline);
		}

		$class = 'nav nav-pills nav-pills-condensed';
		if ($this->arguments['class'])
			$class .= ' ' . $this->arguments['class'];
		$this->tag->addAttribute('class', $class);

		$content = '';
		foreach ($rootline as $element)
		{
			$content .= $this->renderNavigationNode($element);
		}
		$content .= '';

		$this->tag->setContent($content);
		return $this->tag->render();
	}



	protected function renderNavigationNode($object)
	{
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum)
		{
			$controller = 'Forum';
			$arguments = array('forum' => $object);
			$icon = 'tx-mmforum-icon-16-forum';
		}
		else
		{
			$controller = 'Topic';
			$arguments = array('topic' => $object);
			$icon = 'tx-mmforum-icon-16-topic';
		}

		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder
				->reset()
				->setTargetPageUid((int) $this->settings['pids']['Forum'])
				->uriFor('show', $arguments, $controller);

		return '<li><a href="' . $uri . '"><i class="' . $icon . '"></i> ' . $object->getTitle() . '</a></li>';
	}



}
