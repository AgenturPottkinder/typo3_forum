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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class ReportIconViewHelper extends AbstractViewHelper
{

    use CompileWithContentArgumentAndRenderStatic;
    
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     *
     */
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
     * renderStatic.
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $report = $arguments['report'];

        $renderArguemnts['typoscriptObjectPath'] = self::TYPOSCRIPT_PATH;

        return self::getCObjectViewHelper()::renderStatic($renderArguemnts, $renderChildrenClosure, $renderingContext);
    }


    /**
     *
     * Generates a data array that will be passed to the typoscript object for
     * rendering the icon.
     * @param Report $report The report for which the icon is to be displayed.
     * @return array The data array for the typoscript object.
     *
     */
    protected static function getDataArray(Report $report = null, array $arguments = [])
    {
        $data = [];
        if (!is_null($report)) {
            $data = [
                'statusIcon' => $report->getWorkflowStatus()->getIconFullpath(),
                'width' => $arguments['width'],
                'alt' => $arguments['alt'],
            ];
        }

        return $data;
    }

    /**
     * getCObjectViewHelper.
     * @return CObjectViewHelper
     */
    protected static function getCObjectViewHelper()
    {
        return self::getObjectManager()->get(CObjectViewHelper::class);
    }

    /**
     * getObjectManager.
     * @return ObjectManager
     */
    protected static function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
