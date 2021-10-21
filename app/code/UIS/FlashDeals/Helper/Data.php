<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{

    /**
     * Section scope in adminhtml XML configuration.  
     */ 
    const XML_SECTION = 'uis_flashdeals/general';

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_storeManager;

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_customerGroupCollection;

    /**
     * @param \Lof\MarketPlace\Model\SellerFactory $sellerFactory
     */
    protected $_sellerFactory;

    /**
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     */
    protected $_request;

    /**
     * @param \UIS\FlashDeals\Model\FlashDeals flashdealsModel
     */
    protected $_flashDealModel;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * @param  \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $flashdealProductsCollection
     */
    protected $flashdealProductsCollection;

    /**
     * @param  \UIS\Manufacturer\Helper\Data $manufacturerHelper
     */
    protected $_manufacturerHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Group $customerGroupCollection,
        \Lof\MarketPlace\Model\SellerFactory $sellerFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $flashdealProductsCollection,
        \Magento\Customer\Model\Session $customerSession,
        \UIS\FlashDeals\Model\FlashDeals $flashdealsModel,
        \UIS\Manufacturer\Helper\Data $manufacturerHelper
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_customerGroupCollection = $customerGroupCollection;
        $this->_sellerFactory = $sellerFactory;
        $this->_request = $requestInterface;
        $this->_flashDealModel = $flashdealsModel;
        $this->customerSession = $customerSession;
        $this->flashdealProductsCollection = $flashdealProductsCollection;
        $this->_manufacturerHelper = $manufacturerHelper;
    }

    /**
     * Check customer is Seller
     * @param int $customerId 
     * return bool $status
     */
    public function getIsSeller($customerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getIsSeller($customerId);
        }
        return false;
    }

    /**
     * Return Seller Id
     * @param int $customerId 
     * return string
     */
    public function getSellerId($customerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getSellerId($customerId);
        }
        return '';
    }

    /**
     * Return Seller Group Id
     * @param int $customerId 
     * return string
     */
    public function getSellerGroupId($customerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getSellerGroupId($customerId);
        }
        return '';
    }

    /**
     * Check Seller is Manufacturer
     * @param int $customerId 
     * return bool $status
     */
    public function getIsManufacturer($customerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getIsManufacturer($customerId);
        }
        return false;
    }

    /**
     * Check customer is Retailer
     * @param int $customerId 
     * return bool $status
     */
    public function getIsRetailer($customerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getIsRetailer($customerId);
        }
        return false;
    }

    /**
     * Get Seller Page Url Key 
     * @param int $sellerId 
     * return string 
     */
    public function getSellerPageUrlKey($sellerId)
    {
        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getSellerPageUrlKey($sellerId);
        }
        return '';
    }

    /**
     * Check if Customer is in Manufacturer Group
     *
     * @param int $customerGroupId 
     * @return bool 
     */
    public function getCustomerIsManufacturer($customerGroupId)
    {

        if (is_object($this->_manufacturerHelper)) {
            return $this->_manufacturerHelper->getCustomerIsManufacturer($customerGroupId);
        }
        return false;
    }

    /**
     * Return First and Last date of week for current date
     *
     * @return array 
     */
    public function getCurrentWeekFromTo()
    {
        $curDate = new \DateTime('now');
        $weeks = $this->getWeekDates($curDate->format('Y-m-d'));
        $weekStartEnd = [];
        foreach ($weeks as $week) {
            if (in_array($curDate->format('Y-m-d'), $week)) {
                $weekStartEnd = ["from" => $week[0], "to" => $week[count($week) - 1]];
            }
        }

        return $weekStartEnd;
    }

    /**
     * Return array of week dates for required date  
     *
     * @param string $reqDateStr 
     * @return array 
     */
    public function getWeekDates($reqDateStr)
    {
        if (!$reqDateStr) {
            return array();
        }
        $curDate = new \DateTime($reqDateStr);
        $startDate =  new \DateTime('01-' . $curDate->format('m-Y'));
        $endDate = new \DateTime($curDate->format('t-m-Y') . '23:59');

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        $weekNumber = 1;
        $weeks = array();

        foreach ($dateRange as $date) {
            $weeks[$weekNumber][] = $date->format('Y-m-d');
            if ($date->format('w') == 6) {
                $weekNumber++;
            }
        }

        return $weeks;
    }

    /**
     * Return Is Enabled Configuration Value
     * 
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfig('is_enabled');
    }

    /**
     * Return Subscription Product SKU Configuration value
     * @param  string $subscriptionType
     * @return string
     */
    public function getSubscriptionProductSku($subscriptionType)
    {
        $subscriptionProductSku = '';
        switch ($subscriptionType) {
            case 'banner':
                $subscriptionProductSku = $this->getConfig('flashdeal_banner_payment_product_sku');
                break;
            case 'products':
                $subscriptionProductSku = $this->getConfig('flashdeal_products_payment_product_sku');
                break;
            default:
                $subscriptionProductSku = '';
        }
        return $subscriptionProductSku;
    }


    /**
     * Return 'redirect_to_checkout_cart' config value
     * 
     * @return bool 
     */
    public function getIsRedirectToCheckoutCart()
    {
        return (bool)$this->getConfig('redirect_to_checkout_cart');
    }

    /**
     * Return 'reset_checkout' config value
     * 
     * @return bool 
     */
    public function getIsResetCheckout()
    {
        return (bool)$this->getConfig('reset_checkout');
    }

    /**
     * Return 'banner_upload_directory' config value
     * 
     * @return string  
     */
    public function getBannerUploadFolder()
    {
        return $this->getConfig('banner_upload_directory');
    }

    /**
     * Return 'banner_upload_directory' config value
     * 
     * @return string  
     */
    public function getDefaultCustomerGroupDestination()
    {
        return $this->getConfig('default_customer_group_destination');
    }

    /**
     * Return config value by key and store
     *
     * @param string $key
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
    public function getConfig($key, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $result =  $this->scopeConfig->getValue(
            $this::XML_SECTION . '/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $result;
    }


    /**
     * Get Flashdeal related froducts for Flashdeal Object (v1) 
     * 
     * @return array
     */
    public function getFlashdealRelatedProducts()
    {
        $relatedProducts = [];
        if ($this->_request->getParam('flashdeals_id')) {
            $flashdealsModel = $this->_flashDealModel->load($this->_request->getParam('flashdeals_id'));
            $relatedProducts = json_decode($flashdealsModel->getData('flashdeal_product_ids'), true);
        }
        return $relatedProducts;
    }

    /**
     * Check customer session and return customer group ID 
     * 
     * @return int $customerGroupId
     */
    public function getCustomerGroupId()
    {
        $customerGroupId = $this->_manufacturerHelper->getCustomerGroupId();
        return (int)$customerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $retailerGroupId
     */
    public function getConsumerGroupId()
    {
        $manufacturerGroupId = $this->_manufacturerHelper->getConsumerGroupId();
        return (int)$manufacturerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $consumerRetailerGroupId
     */
    public function getConsumerRetailerGroupId()
    {
        $consumerRetailerGroupId = $this->_manufacturerHelper->getConsumerRetailerGroupId();
        return (int)$consumerRetailerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $retailerGroupId
     */
    public function getRetailerGroupId()
    {
        $retailerGroupId = $this->_manufacturerHelper->getRetailerGroupId();
        return (int)$retailerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $retailerGroupId
     */
    public function getManufacturerGroupId()
    {
        $manufacturerGroupId = $this->_manufacturerHelper->getManufacturerGroupId();
        return (int)$manufacturerGroupId;
    }

    /**
     * Get Flashdeal Products Collection
     * 
     * @return \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection || []
     */
    public function getFlashdealProductIds($limit = null)
    {

        if ($this->flashdealProductsCollection->getSize()) {

            $customerGroupId = $this->getCustomerGroupId();

            if (!$this->customerSession->isLoggedIn()) {
                $customerGroupId = $this->getCustomerGroupId();
            }

            if ($customerGroupId) {

                $consumerGroupId = $this->getConsumerGroupId();
                $consumerRetailerGroupId = $this->getConsumerRetailerGroupId(); 
                $retailerGroupId = $this->getRetailerGroupId(); 
                $manufacturerGroupId = $this->getManufacturerGroupId();

                /**
                 * All logic is set due to GOULIIAME wantings. Please refer to Gulliame and Andriy Nayda for fother explanation 
                 */
                switch ($customerGroupId) {
                    case $consumerGroupId:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('in' => [ $consumerGroupId, $consumerRetailerGroupId ]));
                        break;

                    case $retailerGroupId:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('in' => [ $retailerGroupId,  $consumerRetailerGroupId]));
                        break;

                    case $manufacturerGroupId:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('in' => [ $retailerGroupId, $consumerRetailerGroupId]));
                        break;

                    default:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('in' => [ $consumerGroupId, $consumerRetailerGroupId]));
                        break;
                }
            }
            $today =  new \DateTime('NOW');
            $this->flashdealProductsCollection->addFieldToSelect('flashdeal_product_ids')
                ->addFieldToFilter('flashdeal_product_ids', array('neq' => 'NULL'))
                ->addFieldToFilter('flashdeal_product_ids', array('gteq' => 1))
                ->addFieldToFilter('expiration_date', array('gteq' => $today->format('Y-m-d')));

            if ($limit && $limit > 0) {
                $this->flashdealProductsCollection->getSelect()->limit($limit);
            }

            $productIds = $this->flashdealProductsCollection->getData();
            return $productIds;
        }

        return [];
    }

    /**
     * @param int $id
     * @return array $flashdealProductData  
     */
    public function getFlashdealProductDataById($flashdealId) {

        if (!$flashdealId) {
            return [];
        }

        if ($this->flashdealProductsCollection->getSize()) {
            $this->flashdealProductsCollection
                 ->resetData()
                 ->addFieldToSelect('flashdeals_id')
                 ->addFieldToSelect('flashdeal_product_requested_price')
                 ->addFieldToFilter('flashdeals_id', ['eq', $flashdealId])->load();

            $flashdealProductsItemData =  $this->flashdealProductsCollection->getData();
            if (isset($flashdealProductsItemData[0]['flashdeals_id']) ) {
                return $flashdealProductsItemData[0];
            }
        }        

        return [];
    }
}
