<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Controller\Adminhtml\NotificationList;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;
use Codelegacy\ReturnToStockNotification\Api\PersonRepositoryInterface;

class InlineEdit extends Action
{
    const ADMIN_RESOURCE = 'Codelegacy_ReturnToStockNotification::manage_notificationList_save';

    /** @var JsonFactory */
    protected $jsonFactory;
    /** @var PersonRepositoryInterface */
    protected $personRepository;

    public function __construct(
        Action\Context $context,
        JsonFactory $jsonFactory,
        PersonRepositoryInterface $personRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->personRepository = $personRepository;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error      = false;
        $messages   = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error'    => true
            ]);
        }

        foreach (array_keys($postItems) as $personId) {
            $person = $this->personRepository->getById($personId);
            try {
                $personData = $postItems[$personId];
                $this->validatePost($personData, $person, $error, $messages);
                if (!$error) {
                    $this->setPersonData($person, $personData);
                    $this->personRepository->save($person);
                }
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithPersonId($person, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPersonId($person, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPersonId($person, __('Something went wrong while saving the record.'));
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithPersonId(PersonInterface $person, $message)
    {
        return '[Person ID: ' . $person->getPersonId() . '] ' . $message;
    }

    protected function setPersonData(PersonInterface $person, array $personData)
    {
        $person->setFirstName($personData[PersonInterface::FIRST_NAME])
            ->setLastName($personData[PersonInterface::LAST_NAME])
            ->setEmailAddress($personData[PersonInterface::EMAIL_ADDRESS]);
        return $this;
    }

    protected function validatePost(array $personData, PersonInterface $person, &$error, array &$messages)
    {
        if (!($this->validateRequiredEntry($personData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithPersonId($person, $error->getText());
            }
        }
    }

    protected function validateRequiredEntry(array $data)
    {
        $requiredFields = [
            PersonInterface::FIRST_NAME    => __('First Name'),
            PersonInterface::LAST_NAME     => __('Last Name'),
            PersonInterface::EMAIL_ADDRESS => __('Email Address'),
            PersonInterface::PRODUCT_ID    => __('Product ID'),
            PersonInterface::PRODUCT_NAME  => __('Product Name'),
            PersonInterface::STORE_ID      => __('Store ID')
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }
}