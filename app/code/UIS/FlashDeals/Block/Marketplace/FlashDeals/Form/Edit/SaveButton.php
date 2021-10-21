<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Block\Marketplace\FlashDeals\Form\Edit;

use Magento\Ui\Component\Control\Container;

class SaveButton extends GenericButton
{

    /**
     * @return array
     */
    public function getButtonData()
    {

        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'uis_flashdeals_flashdeals_form.uis_flashdeals_flashdeals_form',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::DEFAULT_CONTROL,
            'sort_order' => 90
        ];
    }
}
