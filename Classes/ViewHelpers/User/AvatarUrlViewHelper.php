<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;
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
 * ViewHelper that renders a user's avatar.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage ViewHelpers_User
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class AvatarUrlViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {



	/**
	 * An instance of the Extbase Signal-/Slot-Dispatcher.
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected $slots;



	/**
	 *
	 * Injector for the Signal-/Slot-Dispatcher.
	 *
	 * @param \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
	 *                                 An instance of the Extbase Signal-/Slot-
	 *                                 Dispatcher.
	 * @return void
	 *
	 */
	public function injectSignalSlotDispatcher(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher) {
		$this->slots = $signalSlotDispatcher;
	}



	/**
	 *
	 * Initializes the view helper's arguments.
	 *
	 */
	public function initializeArguments() {
		parent::initializeArguments();
	}



	/**
	 *
	 * Renders the avatar.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                                                               The user whose avatar is to be rendered.
	 * @param  integer                                   $width      The desired avatar width
	 * @param  integer                                   $height     The desired avatar height
	 * @param  integer                                   $minWidth      The desired avatar width
	 * @param  integer                                   $minHeight     The desired avatar height
	 * @param  boolean								     $returnURL     if true just return image URL
	 * @return string              HTML content or URL
	 *
	 */
	public function render(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL ) {
		// if user ist not set
		$avatarFilename = NULL;

		if ($user != NULL) {
			$avatarFilename = $user->getImagePath();
		}

		if ($avatarFilename === NULL) {
			$avatarFilename = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('typo3_forum') . 'Resources/Public/Images/Icons/AvatarEmpty.png';
		}
		return $avatarFilename ;

	}



}
