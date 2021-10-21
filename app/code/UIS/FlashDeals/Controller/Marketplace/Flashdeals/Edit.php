<?php

namespace UIS\FlashDeals\Controller\Marketplace\Flashdeals;

use Magento\Framework\App\Action\Context;

class Edit extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Magento\Framework\App\Action\Session
     */
    protected $session;

    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Lof\MarketPlace\Model\SalesFactory 
     */
    protected $sellerFactory;

    const FLAG_IS_URLS_CHECKED = 'check_url_settings';

    protected $_frontendUrl;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var \Lof\Auction\Helper\Data
     */
    protected $_assignHelper;

    /**
     * @var \UIS\FlashDeals\Helper\Data $uisHelper 
     */
    protected $_uisHelper;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @param Context $context            
     * @param Magento\Framework\App\Action\Session $customerSession            
     * @param PageFactory $resultPageFactory            
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Lof\MarketPlace\Model\SellerFactory $sellerFactory,
        \Magento\Framework\Url $frontendUrl,
        \Lofmp\Auction\Helper\Data $helper,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \UIS\FlashDeals\Helper\Data $uisHelper
    ) {
        parent::__construct($context);
        $this->_assignHelper = $helper;
        $this->_frontendUrl = $frontendUrl;
        $this->_actionFlag = $context->getActionFlag();
        $this->sellerFactory     = $sellerFactory;
        $this->session           = $customerSession;
        $this->formKey           = $formKey;
        $this->resultPageFactory = $resultPageFactory;
        $this->_uisHelper = $uisHelper;
    }

    public function getFrontendUrl($route = '', $params = [])
    {
        return $this->_frontendUrl->getUrl($route, $params);
    }

    /**
     * Redirect to URL
     * @param string $url
     * @return \Magento\Framework\App\ResponseInterface
     */
    protected function _redirectUrl($url)
    {
        $this->getResponse()->setRedirect($url);
        $this->session->setIsUrlNotice($this->_actionFlag->get('', self::FLAG_IS_URLS_CHECKED));
        return $this->getResponse();
    }

    /**
     * Customer login form page
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $customerSession = $this->session;
        $customerId = (int)$customerSession->getId();
        $seller = $this->sellerFactory->create()->load($customerId, 'customer_id');
        $status = $seller->getStatus();

        if ($customerSession->isLoggedIn() && $status == 1 && $this->_uisHelper->getIsManufacturer($customerId)) {

            /**
             * Customer is a manufacturer (group_id = 5) . Render page
             */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Flash Deal'));

            //$this->request->setParam('form_key', $this->formKey->getFormKey());  //Reserved

            return $resultPage;
        } elseif ($customerSession->isLoggedIn() && $status == 1 && !$this->_uisHelper->getIsManufacturer($customerId)) {

            /**
             * Customer is not manufacturer (group_id = 5)
             */
            $this->messageManager->addNotice(__('You must have a manufacturer subscription'));
            $this->_redirectUrl($this->getFrontendUrl('lofmpmembership/buy/index'));
        } elseif ($customerSession->isLoggedIn() && $status == 0) {

            /**
             * Customer is not a seller. Redirect to register as a seller page 
             */
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/becomeseller'));
        } else {

            /**
             * Customer is not logged in. Redirect to login page
             */
            $this->messageManager->addNotice(__('You must have a seller account to access'));
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/login'));
        }
    }
}
