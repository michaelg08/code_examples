<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model;

use Magento\Framework\Api\DataObjectHelper;
use UIS\FlashDeals\Api\Data\FlashDealsInterface;
use UIS\FlashDeals\Api\Data\FlashDealsInterfaceFactory;

class FlashDeals extends \Magento\Framework\Model\AbstractModel
{

    protected $_eventPrefix = 'uis_flashdeals_flashdeals';
    protected $dataObjectHelper;

    protected $flashdealsDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param FlashDealsInterfaceFactory $flashdealsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashDeals $resource
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        FlashDealsInterfaceFactory $flashdealsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \UIS\FlashDeals\Model\ResourceModel\FlashDeals $resource,
        \UIS\FlashDeals\Model\ResourceModel\FlashDeals\Collection $resourceCollection,
        array $data = []
    ) {
        $this->flashdealsDataFactory = $flashdealsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve flashdeals model with flashdeals data
     * @return FlashDealsInterface
     */
    public function getDataModel()
    {
        $flashdealsData = $this->getData();
        
        $flashdealsDataObject = $this->flashdealsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $flashdealsDataObject,
            $flashdealsData,
            FlashDealsInterface::class
        );
        
        return $flashdealsDataObject;
    }
}

