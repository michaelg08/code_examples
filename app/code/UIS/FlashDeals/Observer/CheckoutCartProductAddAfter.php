<?php

namespace UIS\FlashDeals\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class CheckoutCartProductAddAfter 
implements ObserverInterface
{

    /**
     * @param \UIS\FlashDeals\Model\FlashdealProducts $flashdealProductsModel
     */
    protected $_flashdealProductsModel;

    public function __construct( 
        \UIS\FlashDeals\Model\FlashdealProducts $flashdealProductsModel
    ) {
        $this->_flashdealProductsModel = $flashdealProductsModel;
    } 

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {

        $item = $observer->getEvent()->getData('quote_item');

        $product = $observer->getEvent()->getData('product');
        $flashdealId = (int)$product->getData('flashdeal_id');
        $flashdealRequestedPrice = 0; 

        if ($product->getData('is_flashdeal') && $product->getData('flashdeal_id') ) {
            $flashdealProductsModel = $this->_flashdealProductsModel->load($flashdealId); 
            $flashdealRequestedPrice = (int)$flashdealProductsModel->getData('flashdeal_product_requested_price');
        }

        if ($flashdealRequestedPrice) {
            $cartItems = [];
            if($item->getQuote()->getItems()){
                foreach ($item->getQuote()->getItems() as $key => $value) {
                    $cartItems[$value->getSku()] = $value->getQty();
                }
            }
            $item->setOriginalCustomPrice($flashdealRequestedPrice);
            $item->setCustomPrice($flashdealRequestedPrice);
            $item->getProduct()->setIsSuperMode(true);
   
        }
        
        return $this;
    }
}
