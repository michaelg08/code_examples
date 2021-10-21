<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Block\Index;

class FlashdealsProductsList extends \Magento\Framework\View\Element\Template
{

    const MAX_IMAGE_WIDTH = 200;
    const MAX_IMAGE_HEIGHT = 200;
    
    /**
     * \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $flashdealProductsCollection
     */
    protected $flashdealProductsCollection;  

    /**
     * \Magento\Catalog\Helper\Image $imageHelper
     */
    protected $imageHelper;  

    /**
     * \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;  

    /**
     * \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    protected $productCollectionFactory;  

     /**
     * \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    protected $priceHelper;  

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $flashdealProductsCollection,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->flashdealProductsCollection = $flashdealProductsCollection;
        $this->imageHelper = $imageHelper;
        $this->customerSession = $customerSession;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->priceHelper = $priceHelper;
    }

    /**
     * Get Flashdeal Products Collection
     * 
     * @return \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection || []
     */
    public function getFlashdealProductIds($limit = null) 
    {
        
        if ( $this->flashdealProductsCollection->getSize() ) {

            if (!$this->customerSession->getId()){

                // customer is not logged in
                return []; 
            } 

            $customerGroupId = $this->customerSession->getCustomer()->getCustomerGroupId();
            if ($customerGroupId) {
                switch ($customerGroupId) {
                    case 1: 
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('eq', 1));
                        break;
                    case 3:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('eq', 3));
                        break;
                    case 999:
                        $this->flashdealProductsCollection->addFieldToFilter('related_customer_group_id', array('in', array(1,3)));
                }   
                
            } 
                $this->flashdealProductsCollection->addFieldToSelect('flashdeal_product_ids')
                        ->addFieldToFilter('flashdeal_product_ids', array('neq' => 'NULL')) 
                        ->addFieldToFilter('flashdeal_product_ids', array('gteq' => 1)) 
                        ->getSelect()
                        ->limit(6);

                $productIds = $this->flashdealProductsCollection->getData();
                return $productIds;    
        }       

        return [];
    }
    
    /**
     * Get Product Image
     * 
     *@param  \Magento\Catalog\Product
     *@return string $image_url
     */    
    public function getProductImage($product)
    {
        //$image_url = $this->imageHelper->init($product, 'product_page_image_large')->setImageFile($product->getFile())->getUrl();

        //return $image_url;
        $image = $this->imageHelper->init($product, 'category_page_grid')->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false);
        $image->resize(200);
        return $image;
    }  
 
    /**
     * Get store products set as Flashdeal
     * 
     * @param array $productIds 
     */
    public function getFlashdealStoreProducts($productIds) 
    {
        $productCollection = $this->productCollectionFactory->create();
        $ids = [];
        foreach ($productIds as $k => $v ) {
            $ids []= $v;
        }
        $productCollection->addAttributeToFilter( 'entity_id', ['in', $ids] )
                          ->load();
        
        return $productCollection;
    }

    /**
     * Returns price formatted into local currency
     * 
     */
    public function getFormattedPrice($price) {
        return $this->priceHelper->currency($price);
    }

    
}

