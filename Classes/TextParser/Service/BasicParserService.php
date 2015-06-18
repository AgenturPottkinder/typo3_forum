<?php
namespace Mittwald\Typo3Forum\TextParser\Service;
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
 * Text parser class that performs basic text parsing functions, such as
 * HTML escaping, line formatting, etc.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage TextParser_Service
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class BasicParserService extends \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The text.
	 * @var string
	 */
	protected $text;



	/**
	 * Protected parts of the parsed text. In these parts, no parsing will be done.
	 * @var array
	 */
	private $protectedParts = array();



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Creates a new instance of this service.
	 */
	public function __construct() {
		parent::__construct();
	}



	/*
	 * SERVICE METHODS
	 */



	/**
	 * Renders the parsed text.
	 *
	 * @param  string $text The text to be parsed.
	 *
	 * @return string       The parsed text.
	 */
	public function getParsedText($text) {
		$this->text = $text;
		$this->extractProtectedParts();
		$this->escape();
		$this->regUrls();
		$this->paragraphs()->lineBreaks();
		$this->restoreProtectedParts();

		Return $this->text;
	}



	/*
	 * HELPER METHODS
	 */

	function _make_url_clickable_cb($matches) {
		$ret = '';
		$url = $matches[2];

		if ( empty($url) )
			return $matches[0];
		// removed trailing [.,;:] from URL
		if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
			$ret = substr($url, -1);
			$url = substr($url, 0, strlen($url)-1);
		}
		return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
	}

	function _make_web_ftp_clickable_cb($matches) {
		$ret = '';
		$dest = $matches[2];
		$dest = 'http://' . $dest;

		if ( empty($dest) )
			return $matches[0];
		// removed trailing [,;:] from URL
		if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
			$ret = substr($dest, -1);
			$dest = substr($dest, 0, strlen($dest)-1);
		}
		return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
	}

	function _make_email_clickable_cb($matches) {
		$email = $matches[2] . '@' . $matches[3];
		return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
	}

	function regUrls() {
		$ret = ' ' . $this->text;
		// in testing, using arrays here was found to be faster
		$ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_make_url_clickable_cb'), $ret);
		$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_make_web_ftp_clickable_cb'), $ret);
		$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', array(&$this, '_make_email_clickable_cb'), $ret);

		// this one is not in an array because we need it to run last, for cleanup of accidental links within links
		$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
		$ret = trim($ret);
		$this->text = $ret;
	}


	/**
	 * Extracts all protected parts from the text and replaces them with placeholders.
	 *
	 * @return void
	 */
	protected function extractProtectedParts() {
		$pattern = ',\[code language=[a-z0-9]+\](.*?)\[\/code\],is';
		preg_match_all($pattern, $this->text, $this->protectedParts);
		$this->text = preg_replace($pattern, '###MMFORUM_PROTECTED###', $this->text);
	}



	/**
	 * Replaces all placeholders for protected parts with the original contents.
	 *
	 * @return void
	 */
	protected function restoreProtectedParts() {
		while (($s = strpos($this->text, '###MMFORUM_PROTECTED###')) !== FALSE) {
			$this->text = substr_replace($this->text, array_shift($this->protectedParts[0]), $s,
			                             strlen('###MMFORUM_PROTECTED###'));
		}
	}



	/**
	 * Performs simple HTML escaping on the text.
	 *
	 * @return \Mittwald\Typo3Forum\TextParser\Service\BasicParserService
	 *                             $this, for chaining
	 */
	protected function escape() {
		$this->text = htmlspecialchars($this->text);
		Return $this;
	}



	/**
	 * Replaces double line breaks with paragraphs.
	 *
	 * @return \Mittwald\Typo3Forum\TextParser\Service\BasicParserService
	 *                             $this, for chaining
	 */
	protected function paragraphs() {
		$this->text = str_replace("\r", '', $this->text);
		$this->text = preg_replace(';\n{2,};s', "\n\n", $this->text);

		$paragraphs = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode("\n\n", $this->text);
		$this->text = '<p>' . implode('</p><p>', $paragraphs) . '</p>';
		Return $this;
	}



	/**
	 * Replaces single line breaks with <br> tags.
	 *
	 * @return \Mittwald\Typo3Forum\TextParser\Service\BasicParserService
	 *                             $this, for chaining
	 */
	protected function lineBreaks() {
		$this->text = $this->removeUnneccesaryLinebreaks($this->text);
		$this->text = nl2br($this->text);
		Return $this;
	}



	/**
	 * Removes superflous line breaks within the text.
	 *
	 * @param  string $text The text with linebreaks.
	 *
	 * @return string       The text with less linebreaks.
	 */
	protected function removeUnneccesaryLinebreaks($text) {
		$text = preg_replace(',(\[[a-z0-9 ]+\])\s*,is', '$1', $text);
		Return $text;
	}

}
