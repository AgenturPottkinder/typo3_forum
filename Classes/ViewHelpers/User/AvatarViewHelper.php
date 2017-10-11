<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;

/*                                                                    - *
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

use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;

/**
 * ViewHelper that renders a user's avatar.
 */
class AvatarViewHelper extends ImageViewHelper
{

	/**
	 * Initialize arguments.
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerTagAttribute('user', 'Mittwald\Typo3Forum\Domain\Model\User\FrontendUser', 'fe_user object', false, null);
	}

    /**
     * Avatar of user object
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string Rendered tag
     */
    public function render() {
        $avatarFilename = null;
        $user = $this->arguments['user'];
        $height = $this->arguments['height'];
        $width = $this->arguments['width'];

        if (($user != null) && !($user instanceof AnonymousFrontendUser)) {
            $avatarFilename = $user->getImagePath();
        }

        if ($avatarFilename === null) {
            $avatarFilename = ExtensionManagementUtility::siteRelPath('typo3_forum').'Resources/Public/Images/Icons/AvatarEmpty.png';
        }
        if ($height === null) {
            $height = $width;
        }

        $newArguments = array_merge($this->arguments, [
			'src' => $avatarFilename,
			'width' => $width,
			'height' => $height
		]);

        $this->setArguments($newArguments);
        return parent::render();
    }
}
