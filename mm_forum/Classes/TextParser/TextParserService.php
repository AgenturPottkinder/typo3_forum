<?php

Class Tx_MmForum_Domain_Service_TextParserService Implements t3lib_Singleton {

	Protected $typoScriptSetup;
	Protected $configuration;
	Protected $configurationPath = NULL;
	Protected $parsingServices;
	Protected $viewHelperVariableContainer;

	Public Function loadConfiguration($configurationPath) {
		If($configurationPath === $this->configurationPath) Return;

		$this->configurationPath = $configurationPath;
		$configurationManager = Tx_Extbase_Dispatcher::getConfigurationManager();
		$this->typoScriptSetup = $configurationManager->loadTypoScriptSetup();

		$pathSegments = t3lib_div::trimExplode('.', $configurationPath);

		$lastSegment = array_pop($pathSegments);
		$setup = $this->typoScriptSetup;
		foreach ($pathSegments as $segment) {
			if (!array_key_exists($segment . '.', $setup)) {
				throw new Tx_MmForum_Domain_Exception_TextParser_Exception (
					'TypoScript object path "' . htmlspecialchars($configurationPath) . '" does not exist' , 1253191023);
			}
			$setup = $setup[$segment . '.'];
		} $this->configuration = $setup[$lastSegment.'.'];

		ForEach($this->configuration['enabledServices.'] As $className) {
			$newService = t3lib_div::makeInstance($className);
			$newService->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
			$this->parsingServices[] = $newService;
		}
	}

	Public Function injectViewHelperVariableContainer(Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}

	Public Function parseText($text) {
		If($this->configurationPath === NULL)
			Throw New Tx_MmForum_Domain_Exception_TextParser_Exception (
				"The textparser is not configured!", 1284730639);

		$text = nl2br($text);
		ForEach($this->parsingServices As &$parsingService)
			$text = $parsingService->getParsedText($text);

		Return $text;
	}

}

?>
