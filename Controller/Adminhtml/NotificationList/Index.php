<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Controller\Adminhtml\NotificationList;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $dataPersistor;
    protected $pageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);

        $this->dataPersistor = $dataPersistor;
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Codelegacy_ReturnToStockNotification::manage_notificationList');
        $resultPage->addBreadcrumb(__('Return To Stock Notification'), __('Return To Stock Notification'));
        $resultPage->addBreadcrumb(__('Notification List'), __('Notification List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Notification List'));

        $this->dataPersistor->clear('codelegacy_returntostocknotification_notificationList');

        return $resultPage;
    }
}