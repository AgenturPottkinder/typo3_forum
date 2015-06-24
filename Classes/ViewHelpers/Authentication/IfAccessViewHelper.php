<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Authentication;
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
 * ViewHelper that renders its contents if the current user has access to a
 * certain operation on a certain object.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage ViewHelpers_Authentication
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class IfAccessViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * The frontend user repository.
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;

	/**
	 * Renders this ViewHelper
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 *                                                                  The object for which the access is to be checked.
	 * @param  string                                      $accessType  The operation for which to check the access.
	 *
	 * @return string              The ViewHelper contents if the user has access to
	 *                             the specified operation.
	 */
	public function render(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object, $accessType = 'read') {
		if ($object->checkAccess($this->frontendUserRepository->findCurrent(),
			$accessType)) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}

}