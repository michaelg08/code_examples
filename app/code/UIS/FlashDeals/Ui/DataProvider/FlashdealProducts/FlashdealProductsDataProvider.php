<?php

namespace UIS\FlashDeals\Ui\DataProvider\FlashdealProducts;

class FlashdealProductsDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * UIS Flashdeals collection
     *
     * @var \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    /**
     * @var \UIS\Helper\Data $uisHelper
     */
    protected $uisHelper;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $collection
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[] $addFieldStrategies
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     * @param \UIS\Helper\Data $uisHelper
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\Collection $collection,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = [],
        \UIS\FlashDeals\Helper\Data $uisHelper,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->uisHelper = $uisHelper;
        $this->customerSession = $customerSession;
        $this->collection = $collection;

        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;

        if ($this->customerSession->getCustomer()->getId()) {
            $sellerId = $this->uisHelper->getSellerId(
                $this->customerSession->getCustomer()->getId()
            );
            $this->collection->addFieldToFilter('seller_id', array('eq' => $sellerId));
        }
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $items = $this->getCollection()->toArray();
        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items['items']),
        ];
    }

    /**
     * Add field to select
     *
     * @param string|array $field
     * @param string|null $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }

}
