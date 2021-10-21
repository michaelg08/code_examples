<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model;

use Magento\Framework\Api\DataObjectHelper;
use UIS\FlashDeals\Api\Data\FlashdealProductsInterface;
use UIS\FlashDeals\Api\Data\FlashdealProductsInterfaceFactory;

class FlashdealProducts extends \Magento\Framework\Model\AbstractModel
{

    protected $_eventPrefix = 'uis_flashdeals_products';
    protected $dataObjectHelper;

    protected $flashdealProductsDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param FlashDealsInterfaceFactory $flashdealsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts $resource
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        FlashdealProductsInterfaceFactory $flashdealProductsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts $resource,
        \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $resourceCollection,
        array $data = []
    ) {
        $this->flashdealProductsDataFactory = $flashdealProductsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve flashdeals model with flashdeals data
     * @return FlashDealsInterface
     */
    public function getDataModel()
    {
        $flashdealProductsData = $this->getData();
        
        $flashdealProductsDataObject = $this->flashdealsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $flashdealProductsDataObject,
            $flashdealProductsData,
            FlashDealsInterface::class
        );
        
        return $flashdealProductsDataObject;
    }
}

