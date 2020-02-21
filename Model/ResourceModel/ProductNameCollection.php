<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;

class ProductNameCollection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Codelegacy\ReturnToStockNotification\Model\Person::class, 
            Person::class
        );
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->removeAllFieldsFromSelect()
            ->addFieldToSelect([PersonInterface::PRODUCT_ID, PersonInterface::PRODUCT_NAME])
            ->removeFieldFromSelect(PersonInterface::PERSON_ID);

        $this->getSelect()->distinct(true);

        return $this;
    }

    protected function _toOptionArray(
        $valueField = null, 
        $labelField = 'name', 
        $additional = []
    ) {
        $valueField = 'product_id';
        $labelField = 'product_name';

        return parent::_toOptionArray(
            $valueField, 
            $labelField, 
            $additional
        );
    }

    protected function _toOptionHash(
        $valueField = null, 
        $labelField = 'name'
    ) {
        $valueField = 'product_id';
        $labelField = 'product_name';

        return parent::_toOptionHash(
            $valueField, 
            $labelField
        );
    }
}