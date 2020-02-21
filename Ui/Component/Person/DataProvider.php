<?php
/**
 * @category  Codelegacy
 * @package   ${PATH}
 * @copyright Copyright (c) 2019 Mars Symbioscience, a division of Mars, Inc.
 */

namespace Codelegacy\ReturnToStockNotification\Ui\Component\Person;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class DataProvider extends DataProvider
{
    /** @var AuthorizationInterface */
    protected $authorization;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        AuthorizationInterface $authorization,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name, 
            $primaryFieldName, 
            $requestFieldName, 
            $reporting, 
            $searchCriteriaBuilder, 
            $request,
            $filterBuilder, 
            $meta, 
            $data
        );
        $this->authorization = $authorization;
        $this->meta = array_replace_recursive(
            $meta, 
            $this->prepareMetadata()
        );
    }

    protected function prepareMetadata()
    {
        $editor          = false;
        $editorEnabled   = false;
        $editorName      = null;
        $permissionScope = null;
        $metadata        = [];

        switch ($this->name) {
            case 'person_listing_data_source':
                $permissionScope = 'Codelegacy_ReturnToStockNotification::manage_notificationList';
                $editor = 'person_columns';
                break;
        }

        if ($editor && $permissionScope && $this->authorization->isAllowed($permissionScope)) {
            $editorEnabled = true;
        }

        if ($editor) {
            $metadata = [
                $editor => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'editorConfig' => [
                                    'enabled' => $editorEnabled
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $metadata;
    }
}