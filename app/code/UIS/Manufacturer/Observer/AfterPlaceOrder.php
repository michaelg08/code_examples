<?php

namespace UIS\Manufacturer\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class AfterPlaceOrder implements ObserverInterface
{

    protected $_responseFactory;
    protected $_url;

    /**
     * @param \Lofmp\TimeDiscount\Model\ProductFactory $itemsFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param QuoteCollection $quoteCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $order_id = $orderIds[0];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->get('Magento\Sales\Model\Order');
        $orderItems = $order->getAllItems();
        $canRedirect = false;
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getProductType() == 'seller_membership') {
                $canRedirect = true;
            }
        }

        if ($canRedirect) {
            $resultRedirect = $this->responseFactory->create();
            $resultRedirect->setRedirect($this->_url->getUrl('marketplace/catalog/dashboard/'))->sendResponse('200');
        }
        exit();
    }
}
