<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class Tx_MmForum_Domain_Model_Forum_Ads extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {


	/**
	 * Flag if advertisement is visible
	 * @var int
	 */
	protected $active;

	/**
	 * The name of the advertisement
	 * @var string
	 */
	protected $name;

	/**
	 * The alt-text of the advertisement
	 * @var string
	 */
	protected $altText;

	/**
	 * The path of the image
	 * @var string
	 */
	protected $path;

	/**
	 * The category of the advertisement
	 * 0=all, 1=forum, 2=topic
	 * @var int
	 */
	protected $category;



	/**
	 * Gets the flag if ad is visible.
	 * @return int The flag is ad is visible
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * Gets the alt-text of the advertisement
	 * @return string The alt-text of the advertisement.
	 */
	public function getAltText() {
		return $this->altText;
	}


	/**
	 * Gets the absolute name of this advertisement.
	 * @return string The name of the advertisement.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Gets the path of image.
	 * @return string The path of the image.
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Gets the category of this advertisement.
	 * @return int The category of the advertisement.
	 */
	public function getCategory() {
		return $this->category;
	}


	/**
	 * Sets the active flag.
	 *
	 * @param int $active Flag if ad is visible
	 * @return void
	 */
	public function setActive($active) {
		$this->active = intval($active);
	}

	/**
	 * Sets the alt-text.
	 *
	 * @param string $altText Alt-text for the image
	 * @return void
	 */
	public function setAltText($altText) {
		$this->altText = $altText;
	}

	/**
	 * Sets the category.
	 *
	 * @param int $category The category of ad
	 * @return void
	 */
	public function setCategory($category) {
		$this->category = $category;
	}


	/**
	 * Sets the name.
	 *
	 * @param string $name The name of a advertisement
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * Sets the path.
	 *
	 * @param string $path The path to the image
	 * @return void
	 */
	public function setPath($path) {
		$this->path = $path;
	}
}