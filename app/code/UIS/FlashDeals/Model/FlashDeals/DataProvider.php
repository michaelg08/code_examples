<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Model\FlashDeals;

use Magento\Framework\App\Request\DataPersistorInterface;
use UIS\FlashDeals\Model\ResourceModel\FlashDeals\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $modelData = $model->getData();

            if (isset($modelData['image_path'])) {
                $imgData = json_decode($modelData['image_path'], true);

                unset($modelData['image_path']);

                if (isset($imgData['name'])) {
                    $modelData['image_path'][0]['name'] = $imgData['name'];
                }
                $modelData['image_path'][0]['file'] = $imgData['file'];
                $modelData['image_path'][0]['url'] = $imgData['url'];
                $modelData['image_path'][0]['size'] = $imgData['size'];
            }
            
            $this->loadedData[$model->getId()] = $modelData;

            //$this->loadedData[$model->getId()] = $model->getData();
        }

        $data = $this->dataPersistor->get('uis_flashdeals_flashdeals');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('uis_flashdeals_flashdeals');
        }

        return $this->loadedData;
    }
}
