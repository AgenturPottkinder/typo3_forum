<?php


class Tx_MmForum_TextParser_Service_AbstractGeshiService{

	/**
	 * all allowed
	 */
	protected $languages = array('PHP', 'JavaScript', 'TypoScript', 'CSS', 'html4strict');

	/**
	 * @param string $sourcode
	 * @param string $language
	 * @param array $configuration
	 */
	public function getFormattedText($sourceCode, $language = 'TypoScript', $configuration = array()){
		$geshi = new SourceCode($sourceCode, $language);
		$geshi->setStrictMode(false);
		$geshi->setLineNumbering(1);
		return $geshi->getFormatedSourceCode();
	}
}
