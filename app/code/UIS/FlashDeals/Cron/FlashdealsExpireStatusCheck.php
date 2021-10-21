<?php

namespace UIS\FlashDeals\Cron;

use DateTime;

class FlashdealsExpireStatusCheck
{
    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection
     */
    protected $flashdealsCollection;

    public function __construct(
        \UIS\FlashDeals\Helper\Data $uishelper,
        \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection
    ) {
        $this->_uishelper = $uishelper;
        $this->flashdealsCollection = $flashdealsCollection;
    }

    public function execute()
    {
        $this->flashdealsCollection
            ->addFieldToSelect('status')
            ->addFieldToSelect('expiration_date')
            ->addFieldToFilter('status', array('eq' => 1))
            ->load();

        if ($this->flashdealsCollection->getSize()) {
            foreach ($this->flashdealsCollection as $flashdealItem) {
                $today = new DateTime();
                $flashdealExpDate = new DateTime($flashdealItem->getData('expiration_date'));

                if ($flashdealExpDate < $today) {
                    $flashdealItem->setData('status', 0)->save();
                }
            }
        }
    }
}
