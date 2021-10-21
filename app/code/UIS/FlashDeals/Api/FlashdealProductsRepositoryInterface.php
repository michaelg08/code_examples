<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FlashdealProductsRepositoryInterface
{

    /**
     * Save FlashDeals
     * @param \UIS\FlashDealProducts\Api\Data\FlashdealProductsInterface $flashDealProducts
     * @return \UIS\FlashDealProducts\Api\Data\FlashDealProducsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \UIS\FlashDeals\Api\Data\FlashdealProductsInterface $flashDealProducts
    );

    /**
     * Retrieve FlashDealProducts
     * @param string $flashdealProductId
     * @return \UIS\FlashdealProducts\Api\Data\FlashdealProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($flashdealProductId);

    /**
     * Retrieve FlashDeal Products matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete FlashDeals
     * @param \UIS\FlashDeals\Api\Data\FlashDealProductsInterface $flashDeals
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \UIS\FlashDeals\Api\Data\FlashdealProductsInterface $flashDealProducts
    );

    /**
     * Delete FlashDeal Product by ID
     * @param string $flashdealProductId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($flashdealProductId);
}
