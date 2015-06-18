<?php
namespace Mittwald\Typo3Forum\Service\Authentication;
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
 * An interface for authentication services, in case anyone wants to
 * implement his own solution... ;)
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Service
 * @version    $Id: AuthenticationServiceInterface.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
interface AuthenticationServiceInterface {



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 */
	public function assertReadAuthorization(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum
	 */
	public function assertNewTopicAuthorization(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic
	 */
	public function assertNewPostAuthorization(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 */
	public function assertEditPostAuthorization(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 */
	public function assertDeletePostAuthorization(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 */
	public function assertModerationAuthorization(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 */
	public function assertAdministrationAuthorization(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 * @param                                             $action
	 */
	public function assertAuthorization(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object, $action);



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object
	 * @param                                             $action
	 */
	public function checkAuthorization(\Mittwald\Typo3Forum\Domain\Model\AccessibleInterface $object, $action);



}
