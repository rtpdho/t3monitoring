<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ExtensionController
 */
class ExtensionController extends BaseController
{

    /**
     * @var \T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository
     */
    protected $extensionRepository = null;

    /**
     * @param \T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository $extensionRepository
     */
    public function injectExtensionRepository(\T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository $extensionRepository)
    {
        $this->extensionRepository = $extensionRepository;
    }

    /**
     * @param ExtensionFilterDemand $filter
     */
    public function listAction(ExtensionFilterDemand $filter = null): ResponseInterface
    {
        if ($filter === null) {
            $filter = GeneralUtility::makeInstance(ExtensionFilterDemand::class);
        }

        $this->moduleTemplate->assignMultiple([
            'filter' => $filter,
            'extensions' => $this->extensionRepository->findByDemand($filter)
        ]);

        return $this->moduleTemplate->renderResponse('List');
    }

    /**
     * action show
     *
     * @param string $extension
     */
    public function showAction($extension = ''): ResponseInterface
    {
        if (empty($extension)) {
            return $this->redirect('list');
        }

        $versions = $this->extensionRepository->findAllVersionsByName($extension);
        $this->moduleTemplate->assignMultiple([
            'versions' => $versions,
            'latest' => $versions->getFirst(),
        ]);

        return $this->moduleTemplate->renderResponse('Show');
    }
}
