<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Block\Marketplace\FlashDeals\Form;

use DateTime;
use stdClass;

class EditForm extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Customer\Model\Session $customerSession
     */
    protected $_customerSession;

    /**
     * @param \UIS\FlashDeals\Helper\Data $uishelper
     */
    protected $_uishelper;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     */
    protected $_request;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \UIS\FlashDeals\Helper\Data $uishelper,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_uishelper = $uishelper;
        $this->_customerSession = $customerSession;
        $this->_request = $request;
    }

    /** 
     * Prepare layout
     *
     * @return Object
     */
    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Edit Deal'));
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function displayForm()
    {
        return $this->_request->getParam('flashdeals_id');
    }

    public function _tohtml()
    {
        return parent::_toHtml();
    }

    public function getFlashDealData()
    {

        $params = new stdClass;
        $customerId = $this->_customerSession->getId();

        if ($customerId) {
            $params->customer_id = $this->_customerSession->getCustomer()->getId();
            $params->customer_group_id  = $this->_customerSession->getCustomer()->getGroupId();
            $createdAtDate = new DateTime();
            $params->created_at = $createdAtDate->format('m/j/Y');

            if ($this->_uishelper->getSellerId($customerId)) {
                $params->seller_id = (int)$this->_uishelper->getSellerId($customerId);
            }
            if ($this->_uishelper->getSellerGroupId($customerId)) {
                $params->seller_group_id = (int)$this->_uishelper->getSellerGroupId($customerId);
            }
        }

        if ($params && !empty($params->flashdeal_start_date)) {
            $startDate = new DateTime($params->flashdeal_start_date);
            $params->flashdeal_start_date = $startDate->format('m/j/Y');
        }
        if ($params && !empty($params->flashdeal_expiration_date)) {
            $expDate = new DateTime($params->flashdeal_expiration_date);
            $params->flashdeal_expiration_date = $expDate->format('m/j/Y');
        }

        return json_encode($params);
    }
}
