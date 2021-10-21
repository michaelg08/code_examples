<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FlashDealsRepositoryInterface
{

    /**
     * Save FlashDeals
     * @param \UIS\FlashDeals\Api\Data\FlashDealsInterface $flashDeals
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \UIS\FlashDeals\Api\Data\FlashDealsInterface $flashDeals
    );

    /**
     * Retrieve FlashDeals
     * @param string $flashdealsId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($flashdealsId);

    /**
     * Retrieve FlashDeals matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \UIS\FlashDeals\Api\Data\FlashDealsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete FlashDeals
     * @param \UIS\FlashDeals\Api\Data\FlashDealsInterface $flashDeals
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \UIS\FlashDeals\Api\Data\FlashDealsInterface $flashDeals
    );

    /**
     * Delete FlashDeals by ID
     * @param string $flashdealsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($flashdealsId);
}
