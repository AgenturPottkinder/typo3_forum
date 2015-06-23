<?php
namespace Mittwald\Typo3Forum\Controller;

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

class StatsController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Stats\SummaryRepository
	 * @inject
	 */
	protected $summaryRepository;

	/**
	 * Listing Action.
	 * @return void
	 */
	public function listAction() {
		switch ($this->settings['listStats']) {
			default:
			case 'summary':
				$dataset['items'] = $this->summaryRepository->findLatestSummaryItems();
				$partial = 'Stats/Summary';
				break;
		}
		$this->view->assign('partial', $partial);
		$this->view->assign('dataset', $dataset);
	}

}
