<?php

/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $attributeRepository;
    protected $eavSetupFactory;
    protected $searchCriteriaBuilder;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        AttributeRepositoryInterface $attributeRepository,
        SearchCriteriaInterface $searchCriteriaBuilder
    ) {
        $this->attributeRepository   = $attributeRepository;
        $this->eavSetupFactory       = $eavSetupFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $applyTo = [
            'simple',
            'virtual',
            'configurable',
            'grouped',
            'downloadable',
            'bundle'
        ];

        if (class_exists('\Codelegacy\SaROS\Model\Product\Type\Subscription\Simple')) {
            $applyTo = array_merge($applyTo, [
                'codelegacy_saros_simple',
                'codelegacy_saros_virtual',
                'codelegacy_saros_downloadable',
                'codelegacy_saros_configurable',
                'codelegacy_saros_grouped'
            ]);
        }

        $eavSetup->addAttribute(
            Product::ENTITY,
            'codelegacy_rtsn_message',
            [
                'label'            => 'Return To Stock Notification Message',
                'input'            => 'textarea',
                'required'         => false,
                'type'             => 'text',
                'global'           => ScopedAttributeInterface::SCOPE_WEBSITE,
                'visible'          => true,
                'user_defined'     => false,
                'apply_to'         => implode(',', $applyTo),
                'searchable'       => false,
                'filterable'       => false,
                'comparable'       => false,
                'visible_on_front' => false
            ]
        );
        $attrIdMessage = $eavSetup->getAttributeId(
            Product::ENTITY, 
            'codelegacy_rtsn_message'
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'codelegacy_rtsn_force',
            [
                'label'            => 'Force "Out of Stock" Experience',
                'input'            => 'select',
                'required'         => false,
                'type'             => 'int',
                'source'           => Boolean::class,
                'global'           => ScopedAttributeInterface::SCOPE_WEBSITE,
                'visible'          => true,
                'user_defined'     => false,
                'apply_to'         => implode(',', $applyTo),
                'searchable'       => false,
                'filterable'       => false,
                'comparable'       => false,
                'visible_on_front' => false,
                'note'             => 'When enabled, "Out of Stock" message with email capture form (if configured) is shown.'
            ]
        );
        $attrIdForce = $eavSetup->getAttributeId(
            Product::ENTITY, 
            'codelegacy_rtsn_force'
        );

        $setId   = $eavSetup->getDefaultAttributeSetId(Product::ENTITY);
        $groupId = $eavSetup->getDefaultAttributeGroupId(
            Product::ENTITY, 
            $setId
        );

        $eavSetup->addAttributeToGroup(
            Product::ENTITY, 
            $setId, 
            $groupId, 
            $attrIdForce, 
            100
        );
        $eavSetup->addAttributeToGroup(
            Product::ENTITY, 
            $setId, 
            $groupId, 
            $attrIdMessage, 
            110
        );

        $setup->endSetup();
    }
}