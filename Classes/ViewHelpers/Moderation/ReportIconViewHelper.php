<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Moderation;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2018 Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

use Mittwald\Typo3Forum\Domain\Model\Moderation\Report;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;

class ReportIconViewHelper extends AbstractViewHelper
{

    const TYPOSCRIPT_PATH = 'plugin.tx_typo3forum.renderer.icons.report';

    /**
     * initializeArguments.
     */
    public function initializeArguments()
    {
        $this->registerArgument('report', Report::class, 'The report for which the icon is to be rendered.');
        $this->registerArgument('width', 'int', 'Image width');
        $this->registerArgument('alt', 'string', 'Alt text');
    }

    /**
     *
     * Renders the report icon.
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report The report for which the icon is to be rendered.
     * @param integer $width Image width
     * @param string $alt Alt text
     * @return string             The rendered icon.
     *
     */
    public function render()
    {
        $report = $this->arguments['report'];

        return $this->getCObjectViewHelper()
            ->render(self::TYPOSCRIPT_PATH, $this->getDataArray($report));
    }

    /**
     *
     * Generates a data array that will be passed to the typoscript object for
     * rendering the icon.
     * @param Report $report The report for which the icon is to be displayed.
     * @return array The data array for the typoscript object.
     *
     */
    protected function getDataArray(Report $report = null)
    {
        $data = [];
        if (!is_null($report)) {
            $data = [
                'statusIcon' => $report->getWorkflowStatus()->getIconFullpath(),
                'width' => $this->arguments['width'],
                'alt' => $this->arguments['alt'],
            ];
        }

        return $data;
    }

    /**
     * getCObjectViewHelper.
     * @return CObjectViewHelper
     */
    protected function getCObjectViewHelper()
    {
        return $this->objectManager->get(CObjectViewHelper::class);
    }
}
