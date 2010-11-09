<?php

Class Tx_MmForum_ViewHelpers_Form_BbCodeEditorViewHelper
	Extends Tx_Fluid_ViewHelpers_Form_TextareaViewHelper {

	Protected $configurationPath;

	Protected $configuration;

	Protected $panels;

	Protected Function  initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('configuration', 'string', 'Path to TS configuration', FALSE, 'plugin.tx_mmforum.settings.textParsing.editorPanel');
	}

	Public Function render() {
		$this->loadConfiguration($this->arguments['configuration']);

		$content = parent::render();
	}

	Protected Function getParserOptionsPanel() {
		$panelContent = '';

		ForEach($this->panels As $panel) {
			$panelContent .= $panel->render();
		}

		Return $panelContent;
	}

	Protected Function loadConfiguration($configurationPath) {
		If($configurationPath === $this->configurationPath) Return;

		$this->configurationPath = $configurationPath;
		$configurationManager = Tx_Extbase_Dispatcher::getConfigurationManager();
		$setup = $configurationManager->loadTypoScriptSetup();

		$pathSegments = t3lib_div::trimExplode('.', $configurationPath);

		$lastSegment = array_pop($pathSegments);
		foreach ($pathSegments as $segment) {
			if (!array_key_exists($segment . '.', $setup))
				throw new Tx_MmForum_Domain_Exception_TextParser_Exception (
					'TypoScript object path "' . htmlspecialchars($configurationPath) . '" does not exist' , 1253191023);
			$setup = $setup[$segment . '.'];
		} $this->configuration = $setup[$lastSegment.'.'];

		ForEach($this->configuration['panels.'] As $className) {
			$newService = t3lib_div::makeInstance($className);
			If(!$newService InstanceOf Tx_MmForum_TextParser_Panel_AbstractPanel)
				Throw New Tx_Extbase_Object_InvalidClass (
					"All classes in $configurationpath.panels must be instances of Tx_MmForum_TextParser_Panel_AbstractPanel!", 1285143384);

			$newService->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
			$this->panels[] = $newService;
		}
	}

}

?>