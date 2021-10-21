<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use UIS\FlashDeals\Api\Data\FlashdealProductsInterfaceFactory;
use UIS\FlashDeals\Api\Data\FlashdealProductsSearchResultsInterfaceFactory;
use UIS\FlashDeals\Api\FlashdealProductsRepositoryInterface;
use UIS\FlashDeals\Model\ResourceModel\FlashdealProducts as ResourceFlashDealProducts;
use UIS\FlashDeals\Model\ResourceModel\FlashdealProducts\CollectionFactory as FlashdealProductsCollectionFactory;

class FlashdealProductsRepository implements FlashdealProductsRepositoryInterface
{

    protected $dataObjectProcessor;

    protected $dataFlashdealProductsFactory;

    protected $resource;

    protected $flashdealProductCollectionFactory;

    protected $extensionAttributesJoinProcessor;

    protected $flashdealProductsFactory;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataObjectHelper;


    /**
     * @param ResourceFlashDealProducts $resource
     * @param FlashdealProductsFactory $flashdealProductsFactory
     * @param FlashDealsInterfaceFactory $dataFlashDealsFactory
     * @param FlashdealProductsCollectionFactory $flashDealsCollectionFactory
     * @param FlashDealsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceFlashDealProducts $resource,
        FlashdealProductsFactory $flashdealProductsFactory,
        FlashdealProductsInterfaceFactory $dataFlashdealProductsFactory,
        FlashdealProductsCollectionFactory $flashdealProductCollectionFactory,
        FlashdealProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->flashdealProductsFactory = $flashdealProductsFactory;
        $this->flashdealProductCollectionFactory = $flashdealProductCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFlashdealProductsFactory = $dataFlashdealProductsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \UIS\FlashDeals\Api\Data\FlashdealProductsInterface $flashdealProduct
    ) {
        /* if (empty($flashDeals->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $flashDeals->setStoreId($storeId);
        } */
        
        $flashdealProductData = $this->extensibleDataObjectConverter->toNestedArray(
            $flashdealProduct,
            [],
            \UIS\FlashDeals\Api\Data\FlashdealProductsInterface::class
        );
        
        $flashDealsModel = $this->flashDealsFactory->create()->setData($flashdealProductData);
        
        try {
            $this->resource->save($flashDealsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the flashDeals: %1',
                $exception->getMessage()
            ));
        }
        return $flashDealsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($flashDealsId)
    {
        $flashDeals = $this->flashDealsFactory->create();
        $this->resource->load($flashDeals, $flashDealsId);
        if (!$flashDeals->getId()) {
            throw new NoSuchEntityException(__('FlashDeals with id "%1" does not exist.', $flashDealsId));
        }
        return $flashDeals->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->flashDealsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \UIS\FlashDeals\Api\Data\FlashDealsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \UIS\FlashDeals\Api\Data\FlashdealProductsInterface $flashDeals
    ) {
        try {
            $flashDealsModel = $this->flashDealsFactory->create();
            $this->resource->load($flashDealsModel, $flashDeals->getFlashdealsId());
            $this->resource->delete($flashDealsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the FlashDeals: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($flashDealsId)
    {
        return $this->delete($this->get($flashDealsId));
    }
}

