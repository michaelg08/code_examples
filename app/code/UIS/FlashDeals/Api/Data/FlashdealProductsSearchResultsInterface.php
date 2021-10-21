<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Api\Data;

interface FlashdealProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get FlashDeal Products list.
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \UIS\FlashDeals\Api\Data\FlashdealProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
