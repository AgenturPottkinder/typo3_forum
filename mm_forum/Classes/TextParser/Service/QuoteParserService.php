<?php

Class Tx_MmForum_TextParser_Service_QuoteParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {
	
		/**
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $userRepository;

	Public Function  __construct() {
		$this->userRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
	}

	Public Function getParsedText($text) {
		Return preg_replace_callback(
			'/\[quote=([0-9]+)\](.*?)\[\/quote\]\w*/i',
			array($this, 'replaceCallback'),
			$text);
	}

	Protected Function replaceCallback($matches) {
		$user = $this->userRepository->findByUid((int)$matches[1]);

		$arguments = Array(
			'user' => $user, 'quote' => trim($matches[2])
		);

		Return $this->viewHelperVariableContainer->getView()->renderPartial(
			'Format/Quote', '', $arguments, $this->viewHelperVariableContainer);
	}

}

?>
