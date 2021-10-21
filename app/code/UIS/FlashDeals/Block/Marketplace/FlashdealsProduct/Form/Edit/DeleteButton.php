<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Block\Marketplace\FlashdealsProduct\Form\Edit;

use Magento\Ui\Component\Control\Container;

class DeleteButton extends GenericButton 
{

    /**
     * @return array
     */
    public function getButtonData()
    {

        $data = [];
        if ($this->getModelId()) {
            $data = [
                'label' => __('Delete Flash Deal Product'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['flashdeals_id' => $this->getModelId()]);
    }

}

