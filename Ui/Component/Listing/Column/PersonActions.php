<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Codelegacy\ReturnToStockNotification\Api\Data\PersonInterface;

class PersonActions extends Column
{
    const URL_PATH_DELETE = 'codelegacy_returntostocknotification/notificationList/remove';

    /** @var UrlInterface */
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[PersonInterface::PERSON_ID])) {
                    $item[$this->getData('name')] = [
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'id' => $item[PersonInterface::PERSON_ID]
                                ]
                            ),
                            'label' => __('Remove')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}