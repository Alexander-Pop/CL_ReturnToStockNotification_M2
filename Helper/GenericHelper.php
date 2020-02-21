<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class GenericHelper extends AbstractHelper
{
    public function isEnabled() {
        return $this->isModuleOutputEnabled('Codelegacy_ReturnToStockNotification') &&
            $this->scopeConfig->getValue(
                'catalog/codelegacy_returntostocknotification/enable',
                ScopeInterface::SCOPE_STORE
            );
    }
}