<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Controller\Marketplace;

use \Magento\Framework\App\Action\Context;

abstract class FlashDeals extends \Magento\Framework\App\Action\Action
{

    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Framework\View\Result\Page $resultPage
     * @return Magento\Framework\View\Result\Page
     */
    public function initPage($resultPage)
    {
        return $resultPage;
    }
}
