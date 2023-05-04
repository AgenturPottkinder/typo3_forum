<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Pagination;

/*                                                                    - *
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

use Closure;
use Mittwald\Typo3Forum\Helpers\Pagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class PaginateViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('objects', 'array', 'The array of objects to paginate.', true);
        $this->registerArgument('as', 'string', 'Variable name to export the object slice as.', true);
        $this->registerArgument('page', 'int', 'Page of objects to display.', true);
        $this->registerArgument('configuration', 'array', 'Pagination configuration.', false, []);
        $this->registerArgument('configAs', 'string', 'Variable name to export the configuration as, for the pagebrowser.', false, '');
    }

    public static function renderStatic($arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $objects = $arguments['objects'];
        if (method_exists($objects, 'toArray')) {
            $objects = $objects->toArray();
        }
        /** @var Pagination $configuration */
        $pagination = GeneralUtility::makeInstance(Pagination::class, $objects, $arguments['configuration']);
        $pagination->setCurrentPage($arguments['page']);

        // Render contents.
        $configName = $arguments['configAs'];

        $variables = $renderingContext->getVariableProvider();
        $variables->add($arguments['as'], $pagination->fetchPage());
        if (!empty($configName)) {
            $variables->add($configName, $pagination);
        }

        $output = $renderChildrenClosure();

        if (!empty($configName)) {
            $variables->remove($configName);
        }
        $variables->remove($arguments['as']);

        return $output;
    }
}
