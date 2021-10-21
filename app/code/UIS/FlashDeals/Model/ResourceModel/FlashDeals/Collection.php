<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\ResourceModel\FlashDeals;

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
            \UIS\FlashDeals\Model\FlashDeals::class,
            \UIS\FlashDeals\Model\ResourceModel\FlashDeals::class
        );
    }
}

