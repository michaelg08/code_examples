<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\ResourceModel;

class FlashdealProducts extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('uis_flashdeals_products', 'flashdeals_id');
    }
}

