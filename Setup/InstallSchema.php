<?php
/* Glory to Ukraine! Glory to the heros! */

namespace Codelegacy\ReturnToStockNotification\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable($setup->getTable('codelegacy_returntostocknotification_person'))
            ->setComment('Codelegacy Return To Stock Notification Person')
            ->addColumn(
                'person_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true
                ],
                'Person ID'
            )
            ->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true
                ],
                'Product ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true
                ],
                'Store ID'
            )
            ->addColumn(
                'product_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Product Name'
            )
            ->addColumn(
                'first_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'First Name'
            )
            ->addColumn(
                'last_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Last Name'
            )
            ->addColumn(
                'email_address',
                Table::TYPE_TEXT,
                Table::MAX_TEXT_SIZE,
                [
                    'nullable' => false
                ],
                'Email Address'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                [
                    'nullable' => true,
                    'default'  => null
                ],
                'Created At'
            );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}