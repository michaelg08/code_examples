<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


//use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;
//use \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;

use \Lof\MarketPlace\Ui\DataProvider\Product\Seller\ProductDataProvider;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

namespace UIS\FlashDeals\Ui\DataProvider\Product\FlashdealRelated;
//Magento\Catalog\Ui\DataProvider\Product\Related;

/**
 * Class RelatedDataProvider
 *
 * @api
 * @since 101.0.0
 */
class FlashdealRelatedDataProvider extends \Lof\MarketPlace\Ui\DataProvider\Product\Seller\ProductDataProvider
{
    /**
     * \UIS\FlashDeals\Model\FlashDeals $flashdealsModel
     */
    protected $_flashdealsModel; 

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[] $addFieldStrategies
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Lof\MarketPlace\Helper\Data $helper,
        \UIS\FlashDeals\Helper\Data $uishelper, 
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $helper, $addFieldStrategies,$addFilterStrategies,$meta,$data);
        if ($uishelper->getFlashdealRelatedProducts()) {

            $attributeValues = [];
            foreach ($uishelper->getFlashdealRelatedProducts() as $k => $v ) {
                $attributeValues[] = $v;
            } 
            $this->collection->addAttributeToFilter('entity_id', array('in', $attributeValues));
        }
      
/**     
        if($helper->getSellerId()) {
            $this->collection->addAttributeToFilter('seller_id',$helper->getSellerId());
        } else {
            $this->collection->addAttributeToFilter('seller_id',0);
        }
**/

        /*Join with vendor table.*/
    }
}
