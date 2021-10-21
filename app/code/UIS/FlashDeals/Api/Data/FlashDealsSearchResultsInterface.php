<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Api\Data;

interface FlashDealsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get FlashDeals list.
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \UIS\FlashDeals\Api\Data\FlashDealsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
