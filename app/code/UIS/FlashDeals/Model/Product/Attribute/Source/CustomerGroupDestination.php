<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\Product\Attribute\Source;

class CustomerGroupDestination extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * LOF Marketplace Groups reference  
     **/
    protected $marketplaceSellerGroup; 

    /**
     * Magento Customer Groups reference  
     **/
    protected $groupFactory; 
    
    public function __construct(
        \Lof\MarketPlace\Model\Group $marketplaceSellerGroup,
	\Magento\Customer\Model\GroupFactory $groupFactory

    ) {
	$this->marketplaceSellerGroup = $marketplaceSellerGroup;
	$this->groupFactory = $groupFactory;
    } 

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
	$groupList = $this->getCustomerGroupList();
	foreach ($groupList as $group) {

    	    if (!in_array($group->getData('customer_group_code'), ['Manufacturer', 'NOT LOGGED IN'])) {
	        $this->_options[] = [ 'value' => $group->getData('customer_group_id'),  'label' => $group->getData('customer_group_code') ];
    	    }
	}
    
        $this->_options[] = [ 'value' => 999,  'label' => __('Consumer & Retailer') ]; 
        return $this->_options;
    }

    /**
     * getAllOptions 
     *
     * @return array
     */
    public function toOptionArray() {
        return $this->getAllOptions();
    }

    public function getMarketplaceSellersGroupList(){
        $collection = $this->sellerGroup
             ->getCollection()
             ->addFieldToFilter('status',1) 
             ->setOrder('position','ASC')
             ->load();
        
        return $collection;
    }

    public function getCustomerGroupList(){
        $collection = $this->groupFactory
             ->create()
             ->getCollection()
             //->setOrder('position','ASC')
             ->load();
        
        return $collection;
    }
}

