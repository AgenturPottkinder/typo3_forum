<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2011 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Search
	 * @version    $Id: PostController.php 39978 2010-11-09 14:19:52Z mhelmich $
	 *
	 * @copyright  2011 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 */

interface Tx_MmForum_Search_Indexing_IndexerInterface {

	public function addPost(Tx_MmForum_Domain_Model_Forum_Post $post);
	
	public function removePost(Tx_MmForum_Domain_Model_Forum_Post $post);

}
