<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\ResourceModel\FlashdealProducts;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'flashdeals_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \UIS\FlashDeals\Model\FlashdealProducts::class,
            \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts::class
        );
    }
}

