<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Model;

use Magento\Framework\Model\AbstractModel;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;

/**
 * Class Person
 *
 * @method string getCreatedAt()
 * @method string getEmailAddress()
 * @method string getFirstName()
 * @method string getLastName()
 * @method integer getPersonId()
 * @method integer getProductId()
 * @method string getProductName()
 * @method integer getStoreId()
 * @method Person setCreatedAt($dateTime)
 * @method Person setEmailAddress($email)
 * @method Person setFirstName($name)
 * @method Person setLastName($name)
 * @method Person setPersonId(integer $id)
 * @method Person setProductId(integer $id)
 * @method Person setProductName($name)
 * @method Person setStoreId(int $storeId)
 *
 * @category  Codelegacy
 * @package   ReturnToStockNotification
 * @copyright Copyright (c) Mars Symbioscience, a division of Mars, Inc.
 */
class Person extends AbstractModel implements PersonInterface
{
    protected $_eventPrefix = 'codelegacy_returntostocknotification_person';

    public function beforeSave()
    {
        if ($this->isObjectNew()) {
            $this->setCreatedAt(\gmdate('Y-m-d H:i:s'));
        }

        return parent::beforeSave();
    }
}