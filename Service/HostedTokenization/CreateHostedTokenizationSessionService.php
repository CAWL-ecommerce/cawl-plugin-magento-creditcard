<?php
declare(strict_types=1);

namespace Cawl\CreditCard\Service\HostedTokenization;

use Magento\Framework\Exception\LocalizedException;
use OnlinePayments\Sdk\Domain\CreateHostedTokenizationRequest;
use OnlinePayments\Sdk\Domain\CreateHostedTokenizationResponse;
use Psr\Log\LoggerInterface;
use Cawl\PaymentCore\Api\ClientProviderInterface;
use Cawl\PaymentCore\Api\Config\WorldlineConfigInterface;
use Cawl\PaymentCore\Api\Service\Services\StoreConnectionServiceInterface;
use Cawl\CreditCard\Api\Service\HostedTokenization\CreateHostedTokenizationSessionServiceInterface;

/**
 * @link https://support.direct.ingenico.com/en/documentation/api/reference/#tag/HostedTokenization/operation/CreateHostedTokenizationApi
 */
class CreateHostedTokenizationSessionService implements CreateHostedTokenizationSessionServiceInterface
{
    /**
     * @var WorldlineConfigInterface
     */
    private $worldlineConfig;

    /**
     * @var ClientProviderInterface
     */
    private $clientProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StoreConnectionServiceInterface
     */
    private $storeConnectionService;

    public function __construct(
        WorldlineConfigInterface $worldlineConfig,
        ClientProviderInterface $clientProvider,
        LoggerInterface $logger,
        StoreConnectionServiceInterface $storeConnectionService
    ) {
        $this->worldlineConfig = $worldlineConfig;
        $this->clientProvider = $clientProvider;
        $this->logger = $logger;
        $this->storeConnectionService = $storeConnectionService;
    }

    /**
     * Create hosted tokenization session
     *
     * @param CreateHostedTokenizationRequest $createHostedTokenizationRequest
     * @param int|null $storeId
     * @return CreateHostedTokenizationResponse
     * @throws LocalizedException
     */
    public function execute(
        CreateHostedTokenizationRequest $createHostedTokenizationRequest,
        ?int $storeId = null
    ): CreateHostedTokenizationResponse {
        if (!$this->storeConnectionService->execute((int) $storeId)) {
            throw new LocalizedException(__('CAWL is not connected for this store.'));
        }

        try {
            return $this->clientProvider->getClient($storeId)
                ->merchant($this->worldlineConfig->getMerchantId($storeId))
                ->hostedTokenization()
                ->createHostedTokenization($createHostedTokenizationRequest);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
            throw new LocalizedException(
                __('CreateHostedTokenizationApi request has failed. Please contact the provider.')
            );
        }
    }
}
