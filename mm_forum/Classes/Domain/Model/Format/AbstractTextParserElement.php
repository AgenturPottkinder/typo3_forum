<?php

Abstract Class Tx_MmForum_Domain_Model_Format_AbstractTextParserElement
	Extends Tx_Extbase_DomainObject_AbstractValueObject {

		/**
		 * @var string
		 */
	Protected $icon;

		/**
		 * @var string
		 */
	Protected $name;

	Public Function getIcon() {
		Return $this->icon;
	}

	Public Function getName() {
		Return $this->name;
	}

	Public Function setIcon($icon) {
		$this->icon = $icon;
	}

	Public Function setName($name) {
		$this->name = $name;
	}

}

?>
