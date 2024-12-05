<?php
declare(strict_types=1);

namespace Cawl\CreditCard\Gateway\Http\Client;

use Magento\Framework\Exception\LocalizedException;
use OnlinePayments\Sdk\Domain\PaymentResponse;
use Psr\Log\LoggerInterface;
use Cawl\CreditCard\Gateway\Request\PaymentDataBuilder;
use Cawl\PaymentCore\Api\Service\Payment\GetPaymentServiceInterface;
use Cawl\PaymentCore\Gateway\Http\Client\AbstractTransaction;

class TransactionSale extends AbstractTransaction
{
    /**
     * @var GetPaymentServiceInterface
     */
    private $getPaymentService;

    public function __construct(
        LoggerInterface $logger,
        GetPaymentServiceInterface $getPaymentService
    ) {
        parent::__construct($logger);
        $this->getPaymentService = $getPaymentService;
    }

    /**
     * Transaction sale
     *
     * @param array $data
     * @return PaymentResponse
     * @throws LocalizedException
     */
    protected function process(array $data): PaymentResponse
    {
        $paymentId = $data[PaymentDataBuilder::PAYMENT_ID] ?? false;
        if (!$paymentId) {
            throw new LocalizedException(__('Payment id is missing'));
        }

        return $this->getPaymentService->execute($paymentId, $data[PaymentDataBuilder::STORE_ID]);
    }
}
