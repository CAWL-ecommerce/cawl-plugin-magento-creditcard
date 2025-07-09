<?php
declare(strict_types=1);

namespace Cawl\CreditCard\Model;

use Magento\Sales\Model\OrderFactory;
use Cawl\PaymentCore\Api\Data\PaymentInterface;
use Cawl\PaymentCore\Api\OrderStateManagerInterface;
use Cawl\PaymentCore\Api\Payment\PaymentIdFormatterInterface;
use Cawl\PaymentCore\Api\QuoteResourceInterface;
use Cawl\PaymentCore\Api\SessionDataManagerInterface;
use Cawl\PaymentCore\Model\OrderState\OrderState;
use Cawl\PaymentCore\Model\QuotePayment\QuotePaymentRepository;

class ReturnRequestProcessor
{
    public const SUCCESS_STATE = 'success';
    public const WAITING_STATE = 'waiting';
    public const FAIL_STATE = 'fail';

    /**
     * @var QuoteResourceInterface
     */
    private $quoteResource;

    /**
     * @var SessionDataManagerInterface
     */
    private $sessionDataManager;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var OrderStateManagerInterface
     */
    private $orderStateManager;

    /**
     * @var PaymentIdFormatterInterface
     */
    private $paymentIdFormatter;

    /**
     * @var SuccessTransactionChecker
     */
    private $successTransactionChecker;

    /**
     * @var QuotePaymentRepository
     */
    private $quotePaymentRepository;

    public function __construct(
        QuoteResourceInterface $quoteResource,
        SessionDataManagerInterface $sessionDataManager,
        OrderFactory $orderFactory,
        OrderStateManagerInterface $orderStateManager,
        PaymentIdFormatterInterface $paymentIdFormatter,
        SuccessTransactionChecker $successTransactionChecker,
        QuotePaymentRepository $quotePaymentRepository
    ) {
        $this->quoteResource = $quoteResource;
        $this->sessionDataManager = $sessionDataManager;
        $this->orderFactory = $orderFactory;
        $this->orderStateManager = $orderStateManager;
        $this->paymentIdFormatter = $paymentIdFormatter;
        $this->successTransactionChecker = $successTransactionChecker;
        $this->quotePaymentRepository = $quotePaymentRepository;
    }

    public function processRequest(?string $paymentId = null, ?string $hostedTokenizationId = null): ?OrderState
    {
        if ($paymentId) {
            $paymentId = $this->paymentIdFormatter->validateAndFormat($paymentId);
            $quote = $this->quoteResource->getQuoteByWorldlinePaymentId($paymentId);
            $this->successTransactionChecker->check($quote, $paymentId);
        } else {
            $quote = $this->quoteResource->getQuoteByWorldlinePaymentId($hostedTokenizationId);
        }

        if (!$quote) {
            return null;
        }

        $payment = $quote->getPayment();

        if ($paymentId) {
            $payment->setAdditionalInformation('payment_id', $paymentId);
            $quotePayment = $this->quotePaymentRepository->getByPaymentIdentifier($paymentId);
            $payment->setMethod($quotePayment->getMethod());
        }

        $quote->setIsActive(false);
        $this->quoteResource->save($quote);

        $paymentCode = (string)$payment->getMethod();
        $paymentProductId = (int)$payment->getAdditionalInformation(PaymentInterface::PAYMENT_PRODUCT_ID);

        $incrementId = (string)$quote->getReservedOrderId();
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        if (!$order->getId()) {
            $this->sessionDataManager->reserveOrder($incrementId);
            return $this->orderStateManager->create($incrementId, $paymentCode, self::WAITING_STATE, $paymentProductId);
        }

        $this->sessionDataManager->setOrderData($order);

        return $this->orderStateManager->create($incrementId, $paymentCode, self::SUCCESS_STATE, $paymentProductId);
    }
}
