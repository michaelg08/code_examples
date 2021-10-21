<?php
/**
 * @category   UIS
 * @package    UIS_FlashDeals
 * @copyright  Copyright (c) 2020 UIS
 * @license    
 */

 namespace UIS\FlashDeals\Model\Product\Type;
 
class FlashdealMembership extends \Magento\Catalog\Model\Product\Type\Virtual {
    /**
     * Product type code.
     */
    const TYPE_CODE = 'flashdeal_membership';
    const TYPE_ID = 'flashdeal_membership';

    /**
     * {@inheritdoc}
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {

    }
}