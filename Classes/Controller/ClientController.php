<?php

namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ClientController
 */
class ClientController extends BaseController
{

    /**
     * Show client
     *
     * @param Client $client
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation $client
     * @return void
     */
    public function showAction(Client $client = null): ResponseInterface
    {
        if ($client === null) {
            // @todo flash message
           return $this->redirect('index', 'Statistic');
        }

        $this->moduleTemplate->assignMultiple([
            'client' => $client,
        ]);

        return $this->moduleTemplate->renderResponse('Show');
    }

    /**
     * Fetch client
     *
     * @param Client $client
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation $client
     */
    public function fetchAction(Client $client = null): ResponseInterface
    {
        if ($client === null) {
            // @todo flash message
            return $this->redirect('index', 'Statistic');
        } else {
            /** @var ClientImport $import */
            $import = GeneralUtility::makeInstance(ClientImport::class);
            $import->run($client->getUid());
            $this->addFlashMessage($this->getLabel('fetchClient.success'));
            return $this->redirect('show', null, null, ['client' => $client]);
        }
    }
}
