<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Block\Flashdeals;

class FlashdealProductPrice extends \Magento\Framework\View\Element\Template
{

     /**
     * \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    protected $priceHelper; 

    /**
     * \UIS\FlashDeals\Helper\Data $uisFlashdealHelper
     */
    protected $uisFlashdealHelper; 

    /**
     * \UIS\Manufacturer\Helper\Data $uisManufacturerHelper
     */
    protected $uisManufacturerHelper; 

    /**
     * \Magento\Framework\Registry $coreRegistry
     */
    protected $_coreRegistry; 
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \UIS\FlashDeals\Helper\Data $uisFlashdealHelper,
        \UIS\Manufacturer\Helper\Data $uisManufacturerHelper,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->priceHelper = $priceHelper;
        $this->uisFlashdealHelper = $uisFlashdealHelper;
        $this->uisManufacturerHelper = $uisManufacturerHelper;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Return current product object from registry
     * 
     * @return null | \Magento\Catalog\Model\Product 
     */
    public function getCurrentProduct()
    {
        return $this->_coreRegistry->registry('product'); 
    } 

    /**
     * Get product is_flashdeal 
     * 
     * @return bool $isFlashdealProduct
     */
    public function getIsFlashdealProduct()
    {
        $isFlashdealProduct = false; 
        $currentProduct = $this->getCurrentProduct();
        if ($currentProduct) {
            $isFlashdealProduct = (bool)$currentProduct->getData('is_flashdeal'); 
        }

        return $isFlashdealProduct;
    } 


    /**
     * Get Flash deal product price 
     * 
     * @return int $flashdealProductPrice
     */
    public function getFlashdealProductPrice() 
    {
        $flashdealProductPrice = null; 
        $currentProduct = $this->getCurrentProduct();
        if ($currentProduct) {
            $flashdealId = $currentProduct->getFlashdealId();

            if ($currentProduct->getData('is_flashdeal') && $flashdealId) {
                $flashdealProductPriceArray = $this->uisFlashdealHelper->getFlashdealProductDataById($flashdealId);
                $flashdealProductPrice = $flashdealProductPriceArray['flashdeal_product_requested_price'];
            }
        }

        return $flashdealProductPrice; 
    }


    /**
     * Returns price formatted into local currency
     * 
     */
    public function getFormattedPrice($price) {
        return $this->priceHelper->currency($price);
    }

    
}

