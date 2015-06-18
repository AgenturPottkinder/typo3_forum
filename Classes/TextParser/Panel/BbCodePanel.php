<?php
namespace Mittwald\Typo3Forum\TextParser\Panel;
/* *
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
 * @package    Typo3Forum
 * @subpackage TextParser_Panel
 * @version    $Id: BasicParserService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class BbCodePanel extends \Mittwald\Typo3Forum\TextParser\Panel\AbstractPanel {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\BBcodeRepository
	 */
	protected $bbCodeRepository = NULL;



	/**
	 * @var array<\Mittwald\Typo3Forum\Domain\Model\Format\BBCode>
	 */
	protected $bbCodes = NULL;



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\BBcodeRepository $bbCodeRepository
	 */
	public function injectBbCodeRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\BBcodeRepository $bbCodeRepository) {
		$this->bbCodeRepository = $bbCodeRepository;
		$this->bbCodes          = $this->bbCodeRepository->findAll();
	}



	/**
	 * @return array
	 */
	public function getItems() {
		$result = array();

		foreach ($this->bbCodes as $bbCode) {
			$result[] = $bbCode->exportForMarkItUp();
		}
		return $result;
	}



}