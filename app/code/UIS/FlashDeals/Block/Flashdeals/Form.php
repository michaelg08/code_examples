<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Block\Flashdeals;

class Form extends \Magento\Framework\View\Element\Template
{

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare layout
     *
     * @return Object
     */
    public function _prepareLayout() {
        $this->pageConfig->getTitle ()->set(__('Edit '));
        return parent::_prepareLayout ();
    }

    /**
     * @return string
     */
    public function displayForm()
    {
        //Your block code
	   return (json_encode(get_class_methods($this)) );  
        //return __('Hello Developer! This how to get the storename: %1 and this is the way to build a url: %2', $this->_storeManager->getStore()->getName(), $this->getUrl('contacts'));
    }

    public function _tohtml(){
        return parent::_toHtml();
    }
}

