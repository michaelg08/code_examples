<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Api\Data;

interface FlashdealProductsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const EXPIRATION_DATE = 'expiration_date';
    const START_DATE = 'start_date';
    const IMAGE_PATH = 'image_path';
    const CMS_BLOCK_ID = 'cms_block_id';
    const FLASHDEALS_ID = 'flashdeals_id';
    const SELLER_ID = 'seller_id';
    const DESCRIPTION = 'description';
    const CUSTOM_LAYOUT = 'custom_layout';
    const SUBSCRIPTION_PRODUCT_ID = 'subscription_product_id';
    const DURATION_UNIT = 'duration_unit';
    const SELLER_GROUP_ID = 'seller_group_id';
    const CREATED_AT = 'created_at';
    const RELATED_CUSTOMER_GROUP_ID = 'related_customer_group_id';
    const ID = 'id';
    const NAME = 'name';
    const DURATION = 'duration';
    const CALENDAR_PERIOD_DAYS = 'calendar_period_days';
    const STATUS = 'status';

    /**
     * Get flashdeal_product_id
     * @return string|null
     */
    public function getFlashdealProductId();

    /**
     * Set flashdeal_product_id
     * @param string $flashdealProductId
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setFlashdealsProductId($flashdealsId);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \UIS\FlashDeals\Api\Data\FlashdealProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \UIS\FlashDeals\Api\Data\FlashdealProductsExtensionInterface $extensionAttributes
    );

    /**
     * Get seller_id
     * @return string|null
     */
    public function getSellerId();

    /**
     * Set seller_id
     * @param string $sellerId
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setSellerId($sellerId);

    /**
     * Get seller_group_id
     * @return string|null
     */
    public function getSellerGroupId();

    /**
     * Set seller_group_id
     * @param string $sellerGroupId
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setSellerGroupId($sellerGroupId);

    /**
     * Get subscription_product_id
     * @return string|null
     */
    public function getSubscriptionProductId();

    /**
     * Set subscription_product_id
     * @param string $subscriptionProductId
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setSubscriptionProductId($subscriptionProductId);

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate();

    /**
     * Set start_date
     * @param string $startDate
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setStartDate($startDate);

    /**
     * Get expiration_date
     * @return string|null
     */
    public function getExpirationDate();

    /**
     * Set expiration_date
     * @param string $expirationDate
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setExpirationDate($expirationDate);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setStatus($status);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setName($name);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setDescription($description);

    /**
     * Get image_path
     * @return string|null
     */
    public function getImagePath();

    /**
     * Set image_path
     * @param string $imagePath
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setImagePath($imagePath);

    /**
     * Get related_customer_group_id
     * @return string|null
     */
    public function getRelatedCustomerGroupId();

    /**
     * Set related_customer_group_id
     * @param string $relatedCustomerGroupId
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setRelatedCustomerGroupId($relatedCustomerGroupId);

    /**
     * Get calendar_period_days
     * @return string|null
     */
    public function getCalendarPeriodDays();

    /**
     * Set calendar_period_days
     * @param string $calendarPeriodDays
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setCalendarPeriodDays($calendarPeriodDays);

    /**
     * Get duration
     * @return string|null
     */
    public function getDuration();

    /**
     * Set duration
     * @param string $duration
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setDuration($duration);

    /**
     * Get duration_unit
     * @return string|null
     */
    public function getDurationUnit();

    /**
     * Set duration_unit
     * @param string $durationUnit
     * @return \UIS\FlashDeals\Api\Data\FlashdealProductsInterface
     */
    public function setDurationUnit($durationUnit);

}

