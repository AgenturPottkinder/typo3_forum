<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage TextParser_Panel
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Tx_MmForum_TextParser_Panel_SyntaxHighlightingPanel extends Tx_MmForum_TextParser_Panel_AbstractPanel {



	/**
	 * TODO
	 *
	 * @var Tx_MmForum_Domain_Repository_Format_SyntaxHighlightingRepository
	 */
	protected $syntaxHighlightingRepository = NULL;



	/**
	 * TODO
	 *
	 * @var array<Tx_MmForum_Domain_Model_Format_SyntaxHighlighting>
	 */
	protected $syntaxHighlightings = NULL;



	/**
	 * TODO
	 *
	 * @param Tx_MmForum_Domain_Repository_Format_SyntaxHighlightingRepository $syntaxHighlightingRepository
	 *
	 * @return void
	 */
	public function injectSyntaxHighlightingRepository(Tx_MmForum_Domain_Repository_Format_SyntaxHighlightingRepository $syntaxHighlightingRepository) {
		$this->syntaxHighlightingRepository = $syntaxHighlightingRepository;
		$this->syntaxHighlightings          = $this->syntaxHighlightingRepository->findAll();
	}



	/**
	 * TODO
	 * @return array<array>
	 */
	public function getItems() {
		$result = array();

		foreach ($this->syntaxHighlightings as $syntaxHighlighting) {
			$result[] = $syntaxHighlighting->exportForMarkItUp();
		}
		return array(array('name'      => $this->settings['title'],
		                   'className' => $this->settings['iconClassName'],
		                   'openWith'  => '[code]',
		                   'closeWith' => '[/code]',
		                   'dropMenu'  => $result));
	}

}