<?php

Class Tx_MmForum_ViewHelpers_Format_FileSizeViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	Protected $suffixes = Array(
		0 => 'B', 1 => 'KiB', 2 => 'MiB', 3 => 'GiB', 4 => 'TiB'
	);

		/**
		 *
		 * @param integer $decimals
		 * @param integer $decimalSeparator
		 * @param integer $thousandsSeparator
		 * @return string
		 *
		 */

	Public Function render($decimals = 2, $decimalSeparator = ',', $thousandsSeparator = '.') {
		$fileSize = $this->renderChildren();
		$suffix   = 0;

		While($fileSize >= 1024) {
			$fileSize /= 1024; $suffix ++;
		}

		Return number_format($fileSize, $decimals, $decimalSeparator, $thousandsSeparator).' '.$this->suffixes[$suffix];
	}

}

?>
