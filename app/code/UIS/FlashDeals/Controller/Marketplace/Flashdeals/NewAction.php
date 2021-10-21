<?php

namespace UIS\FlashDeals\Controller\Marketplace\Flashdeals;

use Magento\Framework\App\Action\Context;


class NewAction extends \Magento\Framework\App\Action\Action
{

    const FLAG_IS_URLS_CHECKED = 'check_url_settings';

    /**
     * @var Magento\Framework\App\Action\Session
     */
    protected $session;

    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
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
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    protected $_productRepository;

    /**
     *
     * @param Context $context            
     * @param Magento\Framework\App\Action\Session $customerSession            
     * @param PageFactory $resultPageFactory            
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Url $frontendUrl,
        \Lofmp\Auction\Helper\Data $helper,
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository

    ) {
        parent::__construct($context);
        $this->_assignHelper = $helper;
        $this->_frontendUrl = $frontendUrl;
        $this->_actionFlag = $context->getActionFlag();
        $this->session           = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->_uishelper = $uishelper;
        $this->_productRepository = $productRepository;
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
        $customerId = $customerSession->getId();
        $isSeller = $this->_uishelper->getIsSeller($customerId);
        $isManufacturer = $this->_uishelper->getIsManufacturer($customerId);

        if ($customerSession->isLoggedIn() && $isSeller && $isManufacturer) {

            /**
             * Customer is a manufacturer (group_id = 5) . Render page
             */
            $subscriptionSku = $this->_uishelper->getSubscriptionProductSku('banner');
            $product = $this->_productRepository->get($subscriptionSku);
            if ($product) {
                $productUrl = $product->getProductUrl();
            }
            if ($productUrl) {
                $this->_redirectUrl($productUrl);
            }
        } elseif ($customerSession->isLoggedIn() && $isSeller && !$isManufacturer) {

            /**
             * Customer is not manufacturer (group_id = 5)
             */
            $this->messageManager->addNotice(__('You must have a manufacturer subscription'));
            $this->_redirectUrl($this->getFrontendUrl('lofmpmembership/buy/index'));
        } elseif ($customerSession->isLoggedIn() && !$isSeller) {

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
