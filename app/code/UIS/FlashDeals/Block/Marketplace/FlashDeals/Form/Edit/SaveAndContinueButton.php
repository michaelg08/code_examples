<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Block\Marketplace\FlashDeals\Form\Edit;

//use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class SaveAndContinueButton extends GenericButton 
{

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80
        ];
    }
}

