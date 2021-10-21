<?php 

declare(strict_types=1);

namespace UIS\FlashDeals\Block\Flashdeals;

class Banner extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \UIS\FlashDeals\Helper\Data $uisHelper
     */
    protected $uisHelper; 

    /**
     * @var \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection
     */
    protected $flashdealsCollection;

    /**
     * @var \Magento\Framework\UrlInterface $urlInterface
     */
    //protected $url; 

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \UIS\FlashDeals\Helper\Data $uisHelper
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection
     * @param  \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \UIS\FlashDeals\Helper\Data $uisHelper, 
        \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $flashdealsCollection,
        \Magento\Customer\Model\Session $customerSession, 
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->uisHelper = $uisHelper; 
        $this->flashdealsCollection = $flashdealsCollection;
        $this->customerSession = $customerSession;
    }

    /**
     * Get FlashDeals Collection Data 
     */
    public function getFlashdealData() {
        if ( $this->flashdealsCollection->getSize() ) {
            $this->flashdealsCollection
                ->addFieldToFilter('status', array('eq' => 1))
                ->load(); 

        //var_dump( $this->flashdealsCollection->getSelect()->__toString() );
        //var_dump( get_class_methods($this->flashdealsCollection->getSelect()->__toString()) );
        //die();                

           return $this->flashdealsCollection;   
        }

        return null; 
    }
    

    /**
     * Get Image Url from JSON object 'image_path'
     * 
     * @param JSON $imagePathJsonParams 
     */
    public function getImageUrl($imagePathJsonParams) {

        $url = ''; 
        if (is_string ($imagePathJsonParams) ) {
            $imagePath = json_decode($imagePathJsonParams); 
            if (is_object($imagePath)) {
                $url = $this->getUrl($imagePath->url);
            }
        }
        return $url;

    }

    /**
     * Get Seller Page url by Customer Id
     */
    public function getSellerPageUrl($sellerId) {
        $urlKey = $this->uisHelper->getSellerPageUrlKey($sellerId);
        $url = '';
        if ($urlKey) {
            $url = $this->getUrl('seller/' . $urlKey . '.html');    
        } 
        return $url;
    }

    /**
     * Validate Flashdeals Related Customer Group 
     * 
     * @param int $relatedCustomerGroupId 
     */
    public function validateRelatedCustomerGroup($relatedCustomerGroupId) {
        $currentCustomerGroupId = $this->customerSession->getCustomer()->getGroupId(); 
        
        $validateStatus = false; 
        if ($relatedCustomerGroupId == $currentCustomerGroupId || $relatedCustomerGroupId == 999 ) {
            $validateStatus = true; 
        }

        return $validateStatus;
    } 

    
}
