<?php

namespace UIS\Manufacturer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\CatalogInventory\Api\StockStateInterface;

class CheckoutCartProductAddAfter implements ObserverInterface
{
    protected $uri;
    protected $responseFactory;
    protected $_urlinterface;

    public function __construct(
        \Zend\Validator\Uri $uri,
        \Magento\Framework\UrlInterface $urlinterface,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->uri = $uri;
        $this->_urlinterface = $urlinterface;
        $this->responseFactory = $responseFactory;
        $this->_request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        if ($quoteItem->getProductType() == 'seller_membership') {
            $resultRedirect = $this->responseFactory->create();
            $resultRedirect->setRedirect($this->_urlinterface->getUrl('checkout'))->sendResponse('200');
        }
        exit();
    }
}
