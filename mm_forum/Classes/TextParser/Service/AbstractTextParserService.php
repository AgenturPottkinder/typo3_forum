<?php

Abstract Class Tx_MmForum_TextParser_Service_AbstractTextParserService Implements t3lib_Singleton {

	Protected $viewHelperVariableContainer;
	
	Public Function injectViewHelperVariableContainer(Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}

	Abstract Function getParsedText($text);

}

?>
