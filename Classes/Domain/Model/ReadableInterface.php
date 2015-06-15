<?php
namespace Mittwald\Typo3Forum\Domain\Model;
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
 * Interface definition for objects that can be read by individual users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model
 * @version    $Id$
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

interface Readableinterface {



	/**
	 * Adds a reader to this object.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The reader.
	 * @return void
	 */
	public function addReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader);



	/**
	 * Removes a reader from this object.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The reader.
	 * @return void
	 */
	public function removeReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader);



	/**
	 * Removes all readers from this object.
	 * @return void
	 */
	public function removeAllReaders();



	/**
	 * Determines whether a certain user (NULL for anonymous) has read this object.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The reader
	 * @return boolean
	 */
	public function hasBeenReadByUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader = NULL);

}
