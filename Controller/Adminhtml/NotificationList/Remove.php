<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Controller\Adminhtml\NotificationList;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Codelegacy\ReturnToStockNotification\Api\PersonRepositoryInterface;
use Psr\Log\LoggerInterface;

class Remove extends Action
{
    /** @var LoggerInterface */
    protected $logger;
    /** @var PersonRepositoryInterface */
    protected $personRepository;

    public function __construct(
        Action\Context $context,
        PersonRepositoryInterface $personRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->logger           = $logger;
        $this->personRepository = $personRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $person = $this->personRepository->getById($this->getRequest()->getParam('id'));

            $this->personRepository->delete($person);

            $this->messageManager->addSuccessMessage(__('Notification request has been removed.'));
        }
        catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}