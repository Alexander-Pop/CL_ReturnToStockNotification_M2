<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Plugin\Catalog\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Product;
use Codelegacy\ReturnToStockNotification\Helper\GenericHelper;

class ProductPlugin
{
    protected $helper;

    /**
     * ProductPlugin constructor.
     *
     * @param GenericHelper $helper
     */
    public function __construct(GenericHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Product $service
     * @param ProductInterface|null $product
     * @return ProductInterface
     */
    public function afterInitProduct(
        Product $service,
        $product = null
    ) {
        if ($product instanceof ProductInterface && $product->getId() && $this->helper->isEnabled()) {
            if ($product->getMssRtsnForce()) {
                $product->setIsSalable(false);
            }
        }

        return $product;
    }
}