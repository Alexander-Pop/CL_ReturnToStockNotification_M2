<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;

interface PersonRepositoryInterface
{
    /**
     * @param PersonInterface $person
     * @return PersonRepositoryInterface
     */
    public function delete(Data\PersonInterface $person);

    /**
     * @param int|ProductInterface $product
     * @param string $email
     * @param int|null $storeId
     * @return boolean
     */
    public function findPersonByEmailAndProduct($product, $email, $storeId=null);

    /**
     * @param int|string $id
     * @return PersonInterface
     * @throws NoSuchEntityException
     * @throws NotFoundException
     */
    public function getById($id);

    /**
     * @return PersonInterface
     */
    public function getNewEmptyItem();

    /**
     * @param PersonInterface $person
     * @return PersonRepositoryInterface
     */
    public function save(Data\PersonInterface $person);
}