<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Block\Index\FlashdealsProducts;

class ProductsList extends \Magento\Framework\View\Element\Template
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
     * \UIS\FlashDeals\Helper\Data $uisHelper
     */
    protected $uisHelper;

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
        \UIS\FlashDeals\Helper\Data $uisHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->flashdealProductsCollection = $flashdealProductsCollection;
        $this->imageHelper = $imageHelper;
        $this->customerSession = $customerSession;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->priceHelper = $priceHelper;
        $this->uisHelper = $uisHelper;
    }

    /**
     * Get Flashdeal Products Collection
     * 
     * @return \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection || []
     */
    public function getFlashdealProductIds($limit = null)
    {
        if ($limit) {
            return $this->uisHelper->getFlashdealProductIds($limit);
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
        if (empty($productIds)) {
            return [];
        }

        $productCollection = $this->productCollectionFactory->create();
        $ids = [];
        foreach ($productIds as $k => $v) {
            $ids[] = $v;
        }
        $productCollection->addAttributeToFilter('entity_id', ['in', $ids])
            ->load();

        return $productCollection;
    }

    /**
     * Returns price formatted into local currency
     * 
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price);
    }
}
