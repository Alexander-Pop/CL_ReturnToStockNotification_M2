<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Controller\Notify;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Codelegacy\ReturnToStockNotification\Api\PersonRepositoryInterface;
use Codelegacy\ReturnToStockNotification\Model\Person;
use Psr\Log\LoggerInterface;

class Me extends Action
{
    /** @var Validator */
    protected $formKeyValidator;
    /** @var LoggerInterface  */
    protected $logger;
    /** @var ManagerInterface */
    protected $messageManager;
    /** @var PersonRepositoryInterface */
    protected $personRepository;
    /** @var ProductRepository */
    protected $productRepository;
    /** @var ScopeConfigInterface */
    protected $scopeConfig;
    /** @var StoreManagerInterface */
    protected $storeManager;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        ManagerInterface $messageManager,
        PersonRepositoryInterface $personRepository,
        ProductRepository $productRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->formKeyValidator  = $formKeyValidator;
        $this->logger            = $logger;
        $this->messageManager    = $messageManager;
        $this->personRepository  = $personRepository;
        $this->productRepository = $productRepository;
        $this->scopeConfig       = $scopeConfig;
        $this->storeManager      = $storeManager;
    }

    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRefererUrl());
        }

        try {
            $storeId = $this->storeManager->getStore()->getId();
            $params = $this->getRequest()->getParams();

            if (
                $this->personRepository->findPersonByEmailAndProduct(
                    $params['product'],
                    $params['email_address'],
                    $storeId
                )
            ) {
                throw new LocalizedException(__('That email is already on the notification list for this product.'));
            }

            $consentEnabled = $this->scopeConfig->getValue(
                'catalog/codelegacy_returntostocknotification/consent_enable',
                ScopeInterface::SCOPE_STORE
            );
            $consentCheckboxEnabled = $this->scopeConfig->getValue(
                'catalog/codelegacy_returntostocknotification/consent_checkbox',
                ScopeInterface::SCOPE_STORE
            );

            if (
                $consentEnabled
                && $consentCheckboxEnabled
                && (!isset($params['consent']) || !$params['consent'])
            ) {
                throw new LocalizedException(__('Please provide your consent for us to contact you.'));
            }

            $product = $this->productRepository->getById($params['product'], false, $storeId);

            if (!$product->getId() || $product->getId() != $params['product']) {
                throw new LocalizedException(__('Product was not found'));
            }

            /** @var Person $person */
            $person = $this->personRepository->getNewEmptyItem();
            $person->setFirstName($params['first_name'])
                ->setLastName($params['last_name'])
                ->setEmailAddress($params['email_address'])
                ->setProductId($product->getId())
                ->setStoreId($storeId)
                ->setProductName($product->getName());

            $this->personRepository->save($person);

            $this->messageManager->addSuccessMessage(__('You have been added to our notification list.'));
        }
        catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('An unknown error has occurred, please try again.'));
        }

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRefererUrl());
    }
}