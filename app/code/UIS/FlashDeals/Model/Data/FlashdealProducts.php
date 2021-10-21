<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\Data;

use UIS\FlashDeals\Api\Data\FlashdealProductsInterface;

class FlashdealProducts extends \Magento\Framework\Api\AbstractExtensibleObject implements FlashdealProductsInterface
{

    /**
     * Get flashdeals_id
     * @return string|null
     */
    public function getFlashdealProductId()
    {
        return $this->_get(self::FLASHDEALS_ID);
    }

    /**
     * Set flashdeals_id
     * @param string $flashdealsId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setFlashdealsProductId($flashdealsId)
    {
        return $this->setData(self::FLASHDEALS_ID, $flashdealsId);
    }

    /**
     * Get id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \UIS\FlashDeals\Api\Data\FlashDealsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \UIS\FlashDeals\Api\Data\FlashDealsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \UIS\FlashDeals\Api\Data\FlashdealProductsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get seller_id
     * @return string|null
     */
    public function getSellerId()
    {
        return $this->_get(self::SELLER_ID);
    }

    /**
     * Set seller_id
     * @param string $sellerId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setSellerId($sellerId)
    {
        return $this->setData(self::SELLER_ID, $sellerId);
    }

    /**
     * Get seller_group_id
     * @return string|null
     */
    public function getSellerGroupId()
    {
        return $this->_get(self::SELLER_GROUP_ID);
    }

    /**
     * Set seller_group_id
     * @param string $sellerGroupId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setSellerGroupId($sellerGroupId)
    {
        return $this->setData(self::SELLER_GROUP_ID, $sellerGroupId);
    }

    /**
     * Get subscription_product_id
     * @return string|null
     */
    public function getSubscriptionProductId()
    {
        return $this->_get(self::SUBSCRIPTION_PRODUCT_ID);
    }

    /**
     * Set subscription_product_id
     * @param string $subscriptionProductId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setSubscriptionProductId($subscriptionProductId)
    {
        return $this->setData(self::SUBSCRIPTION_PRODUCT_ID, $subscriptionProductId);
    }

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate()
    {
        return $this->_get(self::START_DATE);
    }

    /**
     * Set start_date
     * @param string $startDate
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setStartDate($startDate)
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * Get expiration_date
     * @return string|null
     */
    public function getExpirationDate()
    {
        return $this->_get(self::EXPIRATION_DATE);
    }

    /**
     * Set expiration_date
     * @param string $expirationDate
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setExpirationDate($expirationDate)
    {
        return $this->setData(self::EXPIRATION_DATE, $expirationDate);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get image_path
     * @return string|null
     */
    public function getImagePath()
    {
        return $this->_get(self::IMAGE_PATH);
    }

    /**
     * Set image_path
     * @param string $imagePath
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setImagePath($imagePath)
    {
        return $this->setData(self::IMAGE_PATH, $imagePath);
    }

    /**
     * Get related_customer_group_id
     * @return string|null
     */
    public function getRelatedCustomerGroupId()
    {
        return $this->_get(self::RELATED_CUSTOMER_GROUP_ID);
    }

    /**
     * Set related_customer_group_id
     * @param string $relatedCustomerGroupId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setRelatedCustomerGroupId($relatedCustomerGroupId)
    {
        return $this->setData(self::RELATED_CUSTOMER_GROUP_ID, $relatedCustomerGroupId);
    }

    /**
     * Get cms_block_id
     * @return string|null
     */
    public function getCmsBlockId()
    {
        return $this->_get(self::CMS_BLOCK_ID);
    }

    /**
     * Set cms_block_id
     * @param string $cmsBlockId
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setCmsBlockId($cmsBlockId)
    {
        return $this->setData(self::CMS_BLOCK_ID, $cmsBlockId);
    }

    /**
     * Get custom_layout
     * @return string|null
     */
    public function getCustomLayout()
    {
        return $this->_get(self::CUSTOM_LAYOUT);
    }

    /**
     * Set custom_layout
     * @param string $customLayout
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setCustomLayout($customLayout)
    {
        return $this->setData(self::CUSTOM_LAYOUT, $customLayout);
    }

    /**
     * Get calendar_period_days
     * @return string|null
     */
    public function getCalendarPeriodDays()
    {
        return $this->_get(self::CALENDAR_PERIOD_DAYS);
    }

    /**
     * Set calendar_period_days
     * @param string $calendarPeriodDays
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setCalendarPeriodDays($calendarPeriodDays)
    {
        return $this->setData(self::CALENDAR_PERIOD_DAYS, $calendarPeriodDays);
    }

    /**
     * Get duration
     * @return string|null
     */
    public function getDuration()
    {
        return $this->_get(self::DURATION);
    }

    /**
     * Set duration
     * @param string $duration
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setDuration($duration)
    {
        return $this->setData(self::DURATION, $duration);
    }

    /**
     * Get duration_unit
     * @return string|null
     */
    public function getDurationUnit()
    {
        return $this->_get(self::DURATION_UNIT);
    }

    /**
     * Set duration_unit
     * @param string $durationUnit
     * @return \UIS\FlashDeals\Api\Data\FlashDealsInterface
     */
    public function setDurationUnit($durationUnit)
    {
        return $this->setData(self::DURATION_UNIT, $durationUnit);
    }
}

