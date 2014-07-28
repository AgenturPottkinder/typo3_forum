<?php
namespace Mittwald\MmForum\ViewHelpers\Forum;


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
 * ViewHelper that renders a forum icon.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Forum
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class ForumIconViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {



	/**
	 * The frontend user repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;



	/**
	 *
	 * Injects a frontend user repository.
	 * @param  \Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository $frontendUserRepository
	 *                             A frontend user repository.
	 * @return void
	 *
	 */
	public function injectFrontendUserRepository(\Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}



	/**
	 *
	 * Initializes the view helper arguments.
	 * @return void
	 *
	 */
	public function initializeArguments() {

	}



	/**
	 *
	 * Renders the forum icon.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Forum $forum
	 *                                                         The forum for which the icon is to be rendered.
	 * @param  integer                             $width      Image width
	 * @param  string                              $alt        Alt text
	 * @return string             The rendered icon.
	 *
	 */
	public function render(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum = NULL, $width = NULL, $alt = "") {
        $data = $this->getDataArray($forum);

        if($data['new']){
            return parent::render('plugin.tx_mmforum.renderer.icons.forum_new', $data);
        }else{
            return parent::render('plugin.tx_mmforum.renderer.icons.forum', $data);
        }

	}



	/**
	 *
	 * Generates a data array that will be passed to the typoscript object for
	 * rendering the icon.
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Forum $forum
	 *                             The topic for which the icon is to be displayed.
	 * @return array               The data array for the typoscript object.
	 *
	 */
	protected function getDataArray(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum = NULL) {
		if ($forum === NULL) {
			return array();
		} else {
			$user = & $this->frontendUserRepository->findCurrent();
			return array('new'    => !$forum->hasBeenReadByUser($user),
			             'closed' => !$forum->checkNewPostAccess($user));
		}
	}



}

?>
