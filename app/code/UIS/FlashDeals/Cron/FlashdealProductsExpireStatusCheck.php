<?php

namespace UIS\FlashDeals\Cron;

use DateTime;

class FlashdealProductsExpireStatusCheck
{
    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection
     */
    protected $flashdealProductsCollection;

    public function __construct(
        \UIS\FlashDeals\Helper\Data $uishelper,
        \UIS\FlashDeals\Model\ResourceModel\FlashDealProducts\Collection $flashdealProductsCollection
    ) {
        $this->_uishelper = $uishelper;
        $this->flashdealProductsCollection = $flashdealProductsCollection;
    }

    public function execute()
    {
        $this->flashdealProductsCollection
            ->addFieldToSelect('status')
            ->addFieldToSelect('expiration_date')
            ->addFieldToFilter('status', array('eq' => 1))
            ->load();

        if ($this->flashdealProductsCollection->getSize()) {
            foreach ($this->flashdealsCollection as $item) {
                $today = new DateTime();
                $flashdealExpDate = new DateTime($item->getData('expiration_date'));

                if ($flashdealExpDate < $today) {
                    $item->setData('status', 0)->save();
                }
            }
        }
    }
}
