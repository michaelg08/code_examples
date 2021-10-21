<?php
namespace UIS\FlashDeals\Ui\Component\Form\FlashdealProducts;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Options for Products
 */
class ProductSelectOptions implements OptionSourceInterface
{

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * UIS Flashdeals collection
     *
     * @var \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection
     */
    protected $collectionFactory;

    /**
     * @var \Lof\MarketPlace\Helper\Data $lofHelper
     */
    protected $lofHelper;

    /**
     * @var \UIS\Helper\Data $uisHelper
     */
    protected $uisHelper;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * @var \Magento\Catalog\Helper\Image $imageHelper
     */
    protected $imageHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory $productFactory
     */
    protected $productFactory;

    /**
     * @var array
     */
    protected $products;

    /**
     * Construct
     *
     * @param \Lof\MarketPlace\Model\ResourceModel\SellerProduct\CollectionFactory $collectionFactory
     * @param \Lof\MarketPlace\Helper\Data $lofHelper
     * @param \UIS\Helper\Data $uisHelper
     * @param \Magento\Customer\Model\Session $customerSession
     * @param RequestInterface $request
     * @param \Magento\Catalog\Helper\Image
     */
    public function __construct(
        \Lof\MarketPlace\Model\ResourceModel\SellerProduct\CollectionFactory $collectionFactory,
        \Lof\MarketPlace\Helper\Data $lofHelper,
        \UIS\FlashDeals\Helper\Data $uisHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        RequestInterface $request

    ) {
        $this->lofHelper = $lofHelper;
        $this->uisHelper = $uisHelper;
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory; 
        $this->request = $request;

    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getProductsData();
    }

    /**
     * Retrieve products
     *
     * @return array
     */
    protected function getProductsData()
    {

        if ($this->products === null) {
            $productCollection = $this->collectionFactory->create();

            
            if ($this->customerSession->getCustomer()->getId()) {
                $sellerId = $this->uisHelper->getSellerId(
                    $this->customerSession->getCustomer()->getId()
                );
                $productCollection->addFieldToFilter('seller_id', array('eq' => $sellerId));
            }
            
            $productCollection->load();

            $productOptions = [];
        
            foreach ($productCollection as $product) {
                $productId = $product->getProductId();
                if (!isset($productOptions[$productId])) {
                    $productOptions[$productId] = [
                        'value' => $productId
                    ];
                }
                $fullProduct = $this->productFactory->create()->load($productId);
                $imgurl = $this->imageHelper->init($fullProduct, 'product_thumbnail_image')->getUrl();

                $productOptions[$productId]['sku'] = $fullProduct->getSku();
                $productOptions[$productId]['name'] = $product->getName();
                $productOptions[$productId]['thumbnail'] = $imgurl;
                $productOptions[$productId]['label']  = '';

                if ( isset($productOptions[$productId]['sku']) ) {
                    $productOptions[$productId]['label'] .= "SKU: " . $productOptions[$productId]['sku'];
                }
                if ( isset($productOptions[$productId]['name']) ) { 
                    $productOptions[$productId]['label'] .= ", NAME: " . $productOptions[$productId]['name'];
                }
               

            }
            $this->products = $productOptions;
        }

        return $this->products;
    }
 
}