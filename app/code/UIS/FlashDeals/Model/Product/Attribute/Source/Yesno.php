<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model\Product\Attribute\Source;

class CustomerGroupDestination extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [['value' => 1, 'label' => __('Yes')], ['value' => 0, 'label' => __('No')]];
        return $this->_options;
    }

}

