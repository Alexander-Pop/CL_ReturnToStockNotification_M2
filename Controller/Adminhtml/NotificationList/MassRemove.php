<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Controller\Adminhtml\NotificationList;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Codelegacy\ReturnToStockNotification\Api\PersonRepositoryInterface;
use Codelegacy\ReturnToStockNotification\Model\ResourceModel\Person\CollectionFactory;

class MassRemove extends Action
{
    const ADMIN_RESOURCE = 'Codelegacy_ReturnToStockNotification::manage_notificationList_delete';

    /** @var CollectionFactory */
    protected $collectionFactory;
    /** @var Filter */
    protected $filter;
    /** @var PersonRepositoryInterface */
    protected $personRepository;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PersonRepositoryInterface $personRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->filter            = $filter;
        $this->personRepository  = $personRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $person) {
            $this->personRepository->delete($person);
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}