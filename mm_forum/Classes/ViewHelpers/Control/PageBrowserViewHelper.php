<?php

Class Tx_MmForum_ViewHelpers_Control_PageBrowserViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {



		/**
		 *
		 * @param integer $elements
		 * @param integer $itemsPerPage
		 * @param integer $currentPage
		 * @return string
		 *
		 */
	
	Public Function render($elements, $itemsPerPage, $currentPage=1) {

		$output = '';
		$pageCount = ceil($elements / $itemsPerPage);

		$output .= $this->renderChildItemWithPage(1, '«');
		$output .= $this->renderChildItemWithPage(max($currentPage-1,1), '‹');

		For($page = 1; $page <= $pageCount; $page ++) {
			$output .= $this->renderChildItemWithPage($page, $page);
		}

		$output .= $this->renderChildItemWithPage($pageCount, '›');
		$output .= $this->renderChildItemWithPage(min($currentPage+1,$pageCount), '»');

		Return $output;
		
	}

	Private Function renderChildItemWithPage($pageNum, $pageLabel) {
		$this->templateVariableContainer->add('pageLabel', $pageLabel);
		$this->templateVariableContainer->add('page', $pageNum);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove('pageLabel');
		$this->templateVariableContainer->remove('page');
		return $output;
	}

}

?>