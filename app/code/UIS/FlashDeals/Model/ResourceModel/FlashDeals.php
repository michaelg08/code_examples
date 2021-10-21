<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\ResourceModel;

class FlashDeals extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('uis_flashdeals_flashdeals', 'flashdeals_id');
    }
}

