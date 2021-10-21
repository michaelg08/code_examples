<?php

declare(strict_types=1);

namespace UIS\Manufacturer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{

    /**
     * Section scope in adminhtml XML configuration.  
     */
    const XML_SECTION = 'uis_manufacturer/general';

    /**
     * Consumer And Retailer Group IDS 
     */
    const CONSUMER_AND_RETAILER_GROUP_IDS = '999';

    /**
     * @param \UIS\Manufacturer\Helper\Data $uishelper
     */
    protected $_storeManager;
    
    /**
     * @param \UIS\Manufacturer\Helper\Data $uishelper
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
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * @param  \Lof\MarketPlace\Model\GroupFactory $sellerGroupFactory
     */
    protected $sellerGroupFactory;

    /**
     * @param  \UIS\FlashDeals\Model\Product\Attribute\Source\CustomerGroupDestination $customerGroupDestination
     */
    protected $_customerGroupDestination;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Group $customerGroupCollection,
        \Lof\MarketPlace\Model\SellerFactory $sellerFactory,
        \Lof\MarketPlace\Model\GroupFactory $sellerGroupFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Customer\Model\Session $customerSession,
        \UIS\Manufacturer\Model\Product\Attribute\Source\CustomerGroupDestination $customerGroupDestination
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_customerGroupCollection = $customerGroupCollection;
        $this->_sellerFactory = $sellerFactory;
        $this->_sellerGroupFactory = $sellerGroupFactory;
        $this->_request = $requestInterface;
        $this->customerSession = $customerSession;
        $this->_customerGroupDestination = $customerGroupDestination;
    }

    /**
     * Check customer is Seller
     * @param int $customerId 
     * return bool $status
     */
    public function getIsSeller($customerId) 
    {
        $seller = $this->_sellerFactory->create()->load($customerId,'customer_id');
        $status = $seller->getStatus();
        return (bool)$status;
    }

    /**
     * Return Seller Id
     * @param int $customerId 
     * return string
     */
    public function getSellerId($customerId) 
    {
        $seller = $this->_sellerFactory->create()->load($customerId,'customer_id');
        return $seller->getData('seller_id'); 
    }

    /**
     * Return Seller Group Id
     * @param int $customerId 
     * return string
     */
    public function getSellerGroupId($customerId) 
    {
        $seller = $this->_sellerFactory->create()->load($customerId,'customer_id');
        return $seller->getData('group_id'); 
    }

    /**
     * Check Seller is Manufacturer
     * @param int $customerId 
     * return bool $status
     */
    public function getIsManufacturer($customerId) 
    {
        $seller = $this->_sellerFactory->create()->load($customerId,'customer_id');
        if ( (bool)$seller->getStatus() && $seller->getData('group_id')) {
            $sellerGroup = $this->_sellerGroupFactory->create()->load($seller->getData('group_id'), 'group_id');
            if (stripos( 'grp-'. $sellerGroup->getData('name'), 'manufacturer') || stripos( 'grp-'. $sellerGroup->getData('url_key'), 'manufacturer')) {
                return true;
            }
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
        $seller = $this->_sellerFactory->create()->load($customerId,'customer_id');
        if ((bool)$seller->getStatus() && $seller->getData('group_id')) {
            $sellerGroup = $this->_sellerGroupFactory->create()->load($seller->getData('group_id'), 'group_id');
            if (stripos($sellerGroup->getData('name'), 'retailer') || stripos($sellerGroup->getData('url_key'), 'retailer')) {
                return true; 
            }
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
        $sellerPageUrl = '';
        $seller = $this->_sellerFactory->create()->load($sellerId, 'seller_id');
        if ( $seller && $seller->getData('seller_id')) {
            return $seller->getData('url_key');
        } 
        return '';
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
     * Check customer session and return customer group ID 
     * 
     * @return int $customerGroupId
     */
    public function getCustomerGroupId() {

        $customerGroupId = $this->getConsumerGroupId();
        if ($this->customerSession->isLoggedIn()) {
            $customerGroupId = $this->customerSession->getCustomer()->getCustomerGroupId();
        }
        return $customerGroupId;
    }

    /**
     * Check customer session and return customer group ID 
     * 
     * @return int $customerGroupId
     */
    public function getConsumerGroupId() 
    {
        $consumerGroupId = $this->_customerGroupDestination->getCustomerGroupIdByName('Consumer');
        return (int)$consumerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $customerRetailerGroupId
     */
    public function getConsumerRetailerGroupId()
    {
        $customerRetailerGroupId = $this::CONSUMER_AND_RETAILER_GROUP_IDS;
        return (int)$customerRetailerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $retailerGroupId
     */
    public function getRetailerGroupId()
    {
        $retailerGroupId = $this->_customerGroupDestination->getCustomerGroupIdByName('Retailer');
        return (int)$retailerGroupId;
    }

    /**
     * Return Customer & Retailer Group Id
     * 
     * @return int $manufacturerGroupId
     */
    public function getManufacturerGroupId()
    {
        $manufacturerGroupId = $this->_customerGroupDestination->getCustomerGroupIdByName('Manufacturer');
        return (int)$manufacturerGroupId;
    }    

}
