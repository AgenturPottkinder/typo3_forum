<?php
namespace Mittwald\MmForum\ViewHelpers;


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
 * ViewHelper that renders its contents if a certain object is an instance
 * of a specific class.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class IfInstanceOfViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\IfViewHelper {



	/**
	 *
	 * Renders the contents of this view helper if $object is an instance of
	 * $className.
	 *
	 * @param  \TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject $object
	 *                                                                   The object.
	 * @param  string                                       $className   The class.
	 * @return string              HTML content.
	 *
	 */

	public function render(\TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject $object, $className) {
		return $object instanceof $className ? $this->renderThenChild() : $this->renderElseChild();
	}

}

?>