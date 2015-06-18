<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;
/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 * A virtual root forum.
 * This class models a virtual root forum that is the parent forum of all
 * other forums.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_Forum
 * @version    $Id: Forum.php 60797 2012-04-16 18:51:49Z mhelmich $
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
class RootForum extends \Mittwald\Typo3Forum\Domain\Model\Forum\Forum implements \TYPO3\CMS\Core\SingletonInterface {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository = NULL;



	public function injectForumRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository $forumRepository) {
		$this->forumRepository = $forumRepository;
	}



	public function __construct() {
		$this->uid = 0;
	}



	public function getChildren() {
		return $this->forumRepository->findRootForums();
	}





	public function checkAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL, $accessType = 'read') {
		if ($accessType === 'read') {
			return TRUE;
		}

		return FALSE;
		#return TYPO3_MODE === 'BE';
	}



}
