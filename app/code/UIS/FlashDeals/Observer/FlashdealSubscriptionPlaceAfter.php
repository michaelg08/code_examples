<?php

namespace UIS\FlashDeals\Observer;

use DateTime;
use Exception;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class FlashdealSubscriptionPlaceAfter implements ObserverInterface
{

    const BANNER_REDIRECT_URL = 'marketplace/uisflashdeals/flashdeals/edit/';
    const PRODUCT_REDIRECT_URL = 'marketplace/uisflashdeals/flashdealproducts/edit/';

    /**
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     */
    protected $_responseFactory;

    /**
     * @param \Magento\Framework\UrlInterface $url
     */
    protected $_url;

    /**
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    protected $_messageManager;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $_customerSession;

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    protected $_productFactory;

    /**
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    protected $_checkoutSession;

    /**
     * @param \UIS\FlashDeals\Model\FlashDeals $fleshdealModel
     */
    protected $_fleshdealModel;

    /**
     * @param \UIS\FlashDeals\Model\FlashdealProducts $fleshdealProductModel
     */
    protected $_fleshdealProductModel;

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \UIS\FlashDeals\Model\FlashDeals $fleshdealModel,
        \UIS\FlashDeals\Model\FlashdealProducts $fleshdealProductModel
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_uishelper = $uishelper;
        $this->_productFactory = $productFactory;
        $this->_fleshdealModel = $fleshdealModel;
        $this->_fleshdealProductModel = $fleshdealProductModel;
    }


    /**
     * add to cart event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderItems = $order->getAllVisibleItems();

        /**  
         *  Check requested product for it's type ID =  'flashdeal_membership'
         */
        $productId = $this->getFlashdealMembershipProductId($orderItems);
        
        if ($productId == 0) {
            return $this;
        }

        /**
         * Create request params
         */
        $params['order_id'] = $order->getId();
        $params['order_increment_id'] = $order->getIncrementId();
        $customerId = $this->_customerSession->getId();
        if ($customerId) {
            $params['customer_id'] =  $this->_customerSession->getId();
            $params['customer_group_id'] =  $this->_customerSession->getCustomer()->getGroupId();
            $params['seller_id'] = (int)$this->_uishelper->getSellerId($customerId);
            $params['seller_group_id'] = (int)$this->_uishelper->getSellerGroupId($customerId);
        }


        $redirect_url = '';
        $flashDealType = ''; 
        foreach ($orderItems as $orderItem) {
            $productOptions = $orderItem->getProductOptions();

            if ( strstr($orderItem->getSku(), $this->_uishelper->getSubscriptionProductSku('banner')) ) {
                $redirect_url = $this::BANNER_REDIRECT_URL;
                $flashDealType = 'banner';
            } elseif ( strstr($orderItem->getSku(), $this->_uishelper->getSubscriptionProductSku('products')) ) { 
                $redirect_url = $this::PRODUCT_REDIRECT_URL;
                $flashDealType = 'products';
            }

            if ($productOptions && count($productOptions) > 0 && isset($productOptions['info_buyRequest'])) {
                foreach ($productOptions['info_buyRequest'] as $k => $v) {
                    if (in_array($k, ['flashdeal_start_date', 'flashdeal_expiration_date'])) {
                        $tmpDate = new \DateTime($v);
                        $params[$k] = $tmpDate->format('Y-m-d H:i:s');
                    } elseif (in_array($k, ['flashdeal_duration', 'product'])) {
                        $params[$k] = $v;
                    }
                }
            }
        }

        $newFlashDealId = $this->createNewFleshDeal($params, $flashDealType);

        //$this->_registry->register('flashdeal_subscription_params', $params);
        $this->_customerSession->setFlashdealParams(json_encode($params));

        $redirectionUrl = $this->_url->getBaseUrl() . $redirect_url . 'flashdeals_id/' . $newFlashDealId;
        $this->_responseFactory->create()
            ->setRedirect($redirectionUrl)
            ->sendResponse();
        exit();
    }

    /**
     * Check order items for 'flashdeal_membership' product type 
     * 
     * @param \Magento\Sales\Order\Item\Resource\Collection $orderItems
     * return int $productId
     */
    protected function getFlashdealMembershipProductId($orderItems)
    {

        $productId = 0;
        foreach ($orderItems as $orderItem) {
            $product = $orderItem->getProduct();
            if ($product->getTypeId() == 'flashdeal_membership') {
                $productId = $product->getId();
            }
        }
        return $productId;
    }

    /**
     * Create new flash deal using subscription product params   
     * 
     * @param array $params
     * @param string "banner" | "products"  $flashDealType 
     * return int $flashdealId 
     */
    protected function createNewFleshDeal($params, $flashDealType)
    {
        try {

            if ($flashDealType == 'banner') {
                $newFlashDeal = $this->_fleshdealModel; 
            } elseif ( $flashDealType == 'products') {
                $newFlashDeal = $this->_fleshdealProductModel; 
            }

            $collection = $newFlashDeal->getCollection();

            $lastId = $collection->getLastItem()->getData('flashdeals_id');
            $nextId = '1';
            if ($lastId)  {
                $nextId = (int)$lastId + 1;
            } 

            $infoBuyRequestParamsToAttributes = [
                'flashdeal_start_date' => 'start_date',
                'flashdeal_expiration_date' => 'expiration_date',
                'flashdeal_duration' => 'calendar_period_days',
                'product' => 'subscription_product_id'
            ];

            foreach ($params as $k => $v) {
                if (in_array($k, array_keys($infoBuyRequestParamsToAttributes))) {
                    $newFlashDeal->setData($infoBuyRequestParamsToAttributes[$k], $v);
                } else {
                    $newFlashDeal->setData($k, $v);
                }
            }

            $createdAtDate = new DateTime();
            $newFlashDeal->setData('created_at', $createdAtDate->format('m/j/Y'));
            $newFlashDeal->setData('name', 'Product ' . $nextId);
            $newFlashDeal->setData('description', 'Product ' . $nextId);
            $newFlashDeal->setData('status', 1);
            $newFlashDeal->setData('related_customer_group_id', $this->_uishelper->getDefaultCustomerGroupDestination() );

            $newFlashDeal->save();

            return $newFlashDeal->getData('flashdeals_id');
        } catch (Exception $e) {
            var_dump($e);
            die();
        }
    }
}
