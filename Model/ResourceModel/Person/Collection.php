<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Model\ResourceModel\Person;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Codelegacy\ReturnToStockNotification\Model\Person;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'codelegacy_returntostocknotification_person_collection';

    protected function _construct()
    {
        $this->_init(
        	Person::class, 
        	\Codelegacy\ReturnToStockNotification\Model\ResourceModel\Person::class
        );
    }
}