<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Block;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Codelegacy\ReturnToStockNotification\Helper\GenericHelper;

class Notify extends Template
{
    /** @var Registry */
    protected $coreRegistry;
    /** @var FilterProvider */
    protected $filterProvider;
    /** @var GenericHelper */
    protected $helper;

    public function __construct(
        Template\Context $context,
        GenericHelper $helper,
        Registry $coreRegistry,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->coreRegistry   = $coreRegistry;
        $this->filterProvider = $filterProvider;
        $this->helper         = $helper;
    }

    public function getConsentMessage()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $message = $this->_scopeConfig->getValue(
            'catalog/codelegacy_returntostocknotification/consent_message',
            ScopeInterface::SCOPE_STORE
        );
        $message = $this->stripTags(
            $message,
            '<strong><b><em><i><u><a><span><sup><sub>',
            false
        );

        $message = $this->filterProvider->getBlockFilter()->setStoreId($storeId)->filter($message);

        return $message;
    }

    public function getProduct()
    {
        return $this->coreRegistry->registry('product');
    }

    public function getSubmitUrl()
    {
        return $this->getUrl('returntostock/notify/me', ['_current' => true]);
    }

    public function showConsentCheckbox()
    {
        return $this->_scopeConfig->getValue(
            'catalog/codelegacy_returntostocknotification/consent_checkbox',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function showNotificationForm(ProductInterface $product)
    {
        $retval = false;

        if ($this->helper->isEnabled()) {
            if (!$product->isSalable()) {
                $retval = true;
            }
        }

        return $retval;
    }

    public function useConsent()
    {
        return $this->_scopeConfig->getValue(
            'catalog/codelegacy_returntostocknotification/consent_enable',
            ScopeInterface::SCOPE_STORE
        );
    }
}