<?php

Class Tx_MmForum_ViewHelpers_Format_TextParserViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * @var Tx_MmForum_TextParser_TextParserService
		 */
	Protected $textParserService;

	Public Function initialize() {
		parent::initialize();
		$this->textParserService =&
			t3lib_div::makeInstance('Tx_MmForum_TextParser_TextParserService');
		$this->textParserService->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
	}

		/**
		 *
		 * @param boolean $bbCodes
		 * @param boolean $smilies
		 * @param boolean $syntaxHighlighting
		 * @return string
		 *
		 */
	Public Function render($configuration='plugin.tx_mmforum.settings.textParsing') {
		$this->textParserService->loadConfiguration($configuration);
		Return $this->textParserService->parseText(trim($this->renderChildren()));
	}

}

?>
