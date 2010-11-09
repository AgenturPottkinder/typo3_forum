<?php

Class Tx_MmForum_ViewHelpers_Control_BigButtonViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 *
		 * @param string $controller
		 * @param string $action
		 * @param array $arguments
		 * @param string $iconAction
		 *
		 */
	Public Function render($controller, $action, $arguments = Array(), $iconAction=NULL) {
		$arguments = Array ( 'controller' => $controller,
		                     'action' => $action,
		                     'arguments' => $arguments,
		                     'buttonLabel' => $this->renderChildren(),
		                     'iconAction' => $iconAction ? $iconAction : $action,
		                     'imgPath' => t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images');
		Return $this->viewHelperVariableContainer->getView()->renderPartial(
			'Control/BigButton', '', $arguments, $this->viewHelperVariableContainer);
	}

}

?>
