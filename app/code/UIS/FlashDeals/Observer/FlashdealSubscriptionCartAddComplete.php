<?php

namespace UIS\FlashDeals\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class FlashdealSubscriptionCartAddComplete implements ObserverInterface
{

    const REDIRECT_URL = 'checkout/cart/index';
    // const REDIRECT_URL = 'checkout/index/index';

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

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession, 
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
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
        $product = $observer->getProduct();
        if (!$observer->getRequest()->getParam('product')) {
            return $this;
        }

        /**  
         *  Check requested product for it's type ID =  'flashdeal_membership'
         */
        if ($product->getTypeId() != 'flashdeal_membership') {
            return $this;
        }

        if ( $this->_uishelper->getIsRedirectToCheckoutCart() ) {
            $redirectionUrl = $this->_url->getUrl($this::REDIRECT_URL);
            $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
            exit();
        }  
        
        return $this; 
        
    }
}
