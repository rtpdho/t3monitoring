<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Edit Record ViewHelper, see FormEngine logic
 */
class EditRecordViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('parameters', 'string', 'parameters', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $parameters = GeneralUtility::explodeUrl2Array($arguments['parameters']);
        $request = $renderingContext->getRequest();

        $filter = $request->getQueryParams()['filter'] ?? [];
        if (isset( $request->getParsedBody()['filter'])) {
            ArrayUtility::mergeRecursiveWithOverrule($filter, $request->getParsedBody()['filter']);
        }

        $parameters['returnUrl'] = (string)$uriBuilder->buildUriFromRoutePath('/module/tools/t3monitoring', [
            'filter' => $filter
        ]);

        return $uriBuilder->buildUriFromRoute('record_edit', $parameters);
    }
}
