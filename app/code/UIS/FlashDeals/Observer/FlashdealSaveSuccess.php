<?php

namespace UIS\FlashDeals\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class FlashdealSaveSuccess implements ObserverInterface
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

    /**
     * \UIS\FlashDeals\Model\FlashDeals $flashdealsModel
     */
    protected $_flashdealsModel; 

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Item\Collection $itemFactory,
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \UIS\FlashDeals\Model\FlashDeals $flashdealsModel 
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_customerSession = $customerSession;
        $this->_itemFactory = $itemFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_uishelper = $uishelper;
        $this->_productFactory = $productFactory;
        $this->_flashdealsModel = $flashdealsModel;
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
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        /**  
         *  Check required data
         */
        if (!$observer->getData('flashdeals_id') || !$observer->getData('flashdeals_data')) {
            return $this;
        }

        $flashdealId = (int)$observer->getData('flashdeals_id');
        $flashdealData = $observer->getData('flashdeals_data');
        
        if(isset($flashdealData['flashdeal_product_ids'])) {
            $productData = json_decode($flashdealData['flashdeal_product_ids']);
        } else {
            $flashDealModel = $this->_flashdealsModel->load($flashdealId); 
            $productData = json_decode($flashDealModel->getData('flashdeal_product_ids'));  
        }  

        foreach ( $productData as $flashdealProductItem) {
            $product = $this->_productFactory->create()->load($flashdealProductItem->entity_id);
            $product->setData('is_flashdeal', 1);
            $product->save();
        }  
        
        
        //$redirectUrl = 'checkout/cart/index';
        //$redirectionUrl = $this->_url->getUrl($redirectUrl);
        //$this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
        //exit();

        return $this; 
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
