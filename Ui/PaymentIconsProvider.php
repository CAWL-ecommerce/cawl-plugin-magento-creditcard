<?php
declare(strict_types=1);

namespace Cawl\CreditCard\Ui;

use Magento\Framework\App\Area;
use Magento\Framework\View\Asset\Source as AssetSource;
use Cawl\CreditCard\Gateway\Config\Config;
use Cawl\CreditCard\Model\Config\Source\CreditCardTypeOptions;
use Cawl\PaymentCore\Api\Ui\PaymentIconsProviderInterface;
use Cawl\PaymentCore\Api\Ui\PaymentProductsProviderInterface;

class PaymentIconsProvider
{
    /**
     * @var AssetSource
     */
    private $assetSource;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CreditCardTypeOptions
     */
    private $options;

    /**
     * @var PaymentIconsProviderInterface
     */
    private $generalIconsProvider;

    /**
     * @var PaymentProductsProviderInterface
     */
    private $paymentProductsProvider;

    public function __construct(
        AssetSource $assetSource,
        Config $config,
        CreditCardTypeOptions $options,
        PaymentIconsProviderInterface $generalIconsProvider,
        PaymentProductsProviderInterface $paymentProductsProvider
    ) {
        $this->assetSource = $assetSource;
        $this->config = $config;
        $this->options = $options;
        $this->generalIconsProvider = $generalIconsProvider;
        $this->paymentProductsProvider = $paymentProductsProvider;
    }

    public function getIcons(int $storeId): array
    {
        $cCTypes = explode(',', $this->config->getCcTypes($storeId));
        if (empty($cCTypes)) {
            return [];
        }

        $cCTypes = $this->unsetUnavailableCCTypes($cCTypes, $storeId);

        $icons = [];
        $labels = $this->getLabels();
        foreach ($cCTypes as $cCType) {
            $asset = $this->generalIconsProvider->createAsset(
                'Cawl_PaymentCore::images/cc/pay_' . $cCType . '.svg',
                [Area::PARAM_AREA => Area::AREA_FRONTEND]
            );
            if (!$asset) {
                continue;
            }

            $placeholder = $this->assetSource->findSource($asset);
            if ($placeholder) {
                [$width, $height] = $this->generalIconsProvider->getDimensions($asset);
                $icons[$cCType] = [
                    'url' => $asset->getUrl(),
                    'width' => $width,
                    'height' => $height,
                    'title' => $labels[$cCType]
                ];
            }
        }

        return $icons;
    }

    public function unsetUnavailableCCTypes(array $cCTypes, int $storeId): array
    {
        $paymentProducts = $this->paymentProductsProvider->getPaymentProducts($storeId);
        if (!$paymentProducts) {
            return [];
        }

        foreach ($cCTypes as $key => $type) {
            if (isset(CreditCardTypeOptions::PAYMENT_PRODUCTS[$type])
                && !array_key_exists(CreditCardTypeOptions::PAYMENT_PRODUCTS[$type], $paymentProducts)
            ) {
                unset($cCTypes[$key]);
            }
        }

        return $cCTypes;
    }

    public function getLabels(): array
    {
        $labels = [];
        foreach ($this->options->toOptionArray() as $option) {
            $labels[$option['value']] = $option['label'];
        }

        return $labels;
    }
}
