<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;

class Person extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
        	'codelegacy_returntostocknotification_person', 
        	PersonInterface::PERSON_ID
        );
    }
}