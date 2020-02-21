<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Model\StoreManagerInterface;
use Codelegacy\ReturnToStockNotification\Api\Data;
use Codelegacy\ReturnToStockNotification\Api\PersonRepositoryInterface;
use Codelegacy\ReturnToStockNotification\Model\ResourceModel\Person\CollectionFactory;
use Codelegacy\ReturnToStockNotification\Model\ResourceModel\Person;

class PersonRepository implements PersonRepositoryInterface
{
    /** @var CollectionFactory */
    protected $personCollectionFactory;
    /** @var PersonFactory */
    protected $personFactory;
    /** @var ResourceModel\Person */
    protected $resourceModel;
    /** @var StoreManagerInterface */
    protected $storeManager;

    public function __construct(
        PersonFactory $personFactory,
        Person $resourceModel,
        CollectionFactory $personCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->personCollectionFactory = $personCollectionFactory;
        $this->personFactory           = $personFactory;
        $this->resourceModel           = $resourceModel;
        $this->storeManager            = $storeManager;
    }

    /**
     * @param Data\PersonInterface $person
     * @return bool|PersonRepositoryInterface
     * @throws \Exception
     */
    public function delete(Data\PersonInterface $person)
    {
        $this->resourceModel->delete($person);
        return $this;
    }

    /**
     * @param int|ProductInterface $product
     * @param string $email
     * @param null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function findPersonByEmailAndProduct(
        $product, 
        $email, 
        $storeId=null
    ) {
        if ($product instanceof ProductInterface) {
            $product = $product->getId();
        }

        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $matches = $this->personCollectionFactory->create()
            ->addFieldToFilter(Data\PersonInterface::PRODUCT_ID, $product)
            ->addFieldToFilter(Data\PersonInterface::EMAIL_ADDRESS, $email)
            ->addFieldToFilter(Data\PersonInterface::STORE_ID, $storeId)
            ->count();

        return $matches > 0;
    }

    /**
     * @param int|string $id
     * @return \Magento\Framework\DataObject|Data\PersonInterface
     * @throws NoSuchEntityException
     * @throws NotFoundException
     */
    public function getById($id)
    {
        $person = $this->personCollectionFactory->create()
            ->addFieldToFilter(Data\PersonInterface::PERSON_ID, $id)
            ->getFirstItem();

        if (!($person instanceof Data\PersonInterface)) {
            throw new NoSuchEntityException(__('Notification request with that ID does not exist.'));
        }

        if ($person->getPersonId() != $id) {
            throw new NotFoundException(__('Notification request with that ID was not found.'));
        }

        return $person;
    }

    public function getNewEmptyItem()
    {
        return $this->personCollectionFactory->create()->getNewEmptyItem();
    }

    /**
     * @param Data\PersonInterface $person
     * @return $this|PersonRepositoryInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(Data\PersonInterface $person)
    {
        $this->resourceModel->save($person);
        return $this;
    }
}