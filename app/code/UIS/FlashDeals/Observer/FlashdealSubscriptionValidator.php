<?php

namespace UIS\FlashDeals\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class FlashdealSubscriptionValidator implements ObserverInterface
{
    /**
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     */
    protected $__responseFactory;

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
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\ItemFactory $itemFactory
     */
    protected $_itemFactory;

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    protected $_productFactory;

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Item\Collection $itemFactory,
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_customerSession = $customerSession;
        $this->_itemFactory = $itemFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_uishelper = $uishelper;
        $this->_productFactory = $productFactory;
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
        
        /**  
         *  Check requested if request parameter 'product' is not empty. 
         */
        if (!$observer->getRequest()->getParam('product')) {
            return $this;
        }

        /**  
         *  Check requested product for it's type. If it's type != 'flashdeal_membership' add it. 
         */
        $productId = (int)$observer->getRequest()->getParam('product');
        $product = $this->_productFactory->create()->load($productId);
        if ($product->getTypeId() != 'flashdeal_membership') {
            return $this;
        }


        if (!$this->_customerSession->isLoggedIn()) {
            /**  
             *  If Customer is not logged in show error message and redirect to login page. Product is not added to cart.  
             */
            $observer->getRequest()->setParam('product', false);
            $_errorMessage = __('You should sign in before bying subscription.');
            $redirectUrl = 'customer/account/login';
            $this->redirectWithMessage($_errorMessage, $redirectUrl);
            return $this;
        }

        $customerId = $this->_customerSession->getCustomer()->getId();


        if (!$this->_uishelper->getIsSeller($customerId)) {
            /**  
             *  If Customer is not a seller. Redirect him to become a seller page; 
             */
            $observer->getRequest()->setParam('product', false);
            $_errorMessage = __('You should become a marketplace seller first.');
            $redirectUrl = 'lofmarketplace/seller/becomeseller';
            $this->redirectWithMessage($_errorMessage, $redirectUrl);
            return $this;
        }

        if (!$this->_uishelper->getIsManufacturer($customerId)) {
            /**  
             *  If Customer is not manufacturer: show error message and redirect to subscription page. Product is not added to cart.  
             */
            $observer->getRequest()->setParam('product', false);
            $_errorMessage = __('You should be subscribed as a manufacturer.');
            $redirectUrl = 'lofmpmembership/buy/index';
            $this->redirectWithMessage($_errorMessage, $redirectUrl);
            return $this;
        }


        $subscriptionItemsCollection = $this->getCustomerOrderCollection();
        if ($subscriptionItemsCollection->getSize() >= 10) {
            /**  
             *  If Customer already bought 10 subscritions this week: show error message and redirect to subscription page. Product is not added to cart.  
             */
            $observer->getRequest()->setParam('product', false);
            $_errorMessage = __('You already bought 10 subscriptions this week. You can\'t buy more.');
            $redirectUrl = '*/*/*';
            $this->redirectWithMessage($_errorMessage, $redirectUrl);
            return $this;
        }

        if ($this->_uishelper->getIsResetCheckout()) {
            //TODO Add Checkout Session -> resetCheckout() and remove all Quote items (clearQuote moethd)
        }
        
        //$redirectUrl = 'checkout/cart/index';
        //$redirectionUrl = $this->_url->getUrl($redirectUrl);
        //$this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
        //exit();

        return $this; 
    }

    /**
     * Get Order Collection for current week period 
     *
     * @return $this
     */
    protected function getCustomerOrderCollection()
    {
        $orderStatuses = ['new', 'pending', 'processing', 'complete'];
        $customerId = $this->_customerSession->getId();
        $fromTo = $this->_uishelper->getCurrentWeekFromTo();
        $subscriptionSku = $this->_uishelper->getSubscriptionProductSku('products');

        $orderTable = $this->_orderCollectionFactory->create()->getTable('sales_order');
        $orderItemCollection = $this->_itemFactory;

        $orderItemCollection->getSelect()->joinLeft(
            array('sales_flat_order' => $orderTable),
            'main_table.order_id = sales_flat_order.entity_id',
            array('sales_flat_order.status', 'sales_flat_order.customer_group_id')
        );

        $orderItemCollection->addFieldToSelect('*')
            ->addFieldToFilter(
                'customer_id',
                ['eq' => $customerId]
            )        
            ->addFieldToFilter(
                'status',
                ['in' => $orderStatuses]
            )
            ->addFieldToFilter(
                'sales_flat_order.created_at',
                ['gteq' => $fromTo['from']]
            )
            ->addFieldToFilter(
                'sales_flat_order.created_at',
                ['lteq' => $fromTo['to']]

            )
            ->addFieldToFilter(
                'main_table.product_type',
                ['eq' => 'flashdeal_membership']
            )
            ->addFieldToFilter('main_table.sku', ['like' => $subscriptionSku . '%']);
        $orderItemCollection->setOrder(
            'created_at',
            'desc'
        );

        return $orderItemCollection;
    }

    /**
     * Create redirect url and errorMessage
     * 
     * @param string $message  
     * @param string $redirectUrl 
     */
    protected function redirectWithMessage($message, $redirectUrl)
    {
        $this->_messageManager->addError($message);
        $redirectionUrl = $this->_url->getUrl($redirectUrl);
        $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
        exit();
    }
}
