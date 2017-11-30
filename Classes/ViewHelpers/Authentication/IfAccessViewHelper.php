<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Authentication;

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

use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Access;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper that renders its contents if the current user has access to a
 * certain operation on a certain object.
 */
class IfAccessViewHelper extends AbstractViewHelper
{

	protected $escapeOutput = false;

	protected $escapeChildren = false;

	/**
	 * The frontend user repository.
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('object', AccessibleInterface::class, 'Object to check access for', true);
		$this->registerArgument('accessType', 'string', true, Access::TYPE_READ);
	}

	/**
	 * Renders this ViewHelper
	 *
	 * @return string The ViewHelper contents if the user has access to the specified operation.
	 */
	public function render()
	{
		$object = $this->arguments['object'];
		$accessType = $this->arguments['accessType'];
		if ($object->checkAccess($this->frontendUserRepository->findCurrent(), $accessType)) {
			return $this->renderChildren();
		}
	}
}
