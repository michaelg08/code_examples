<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Controller\Marketplace\FlashDeals;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor 

    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        
        

        if ($data) {
            $id = $this->getRequest()->getParam('flashdeals_id');
        
            if (isset ($data['image_path'])) {
                $data['image_path'] = $this->updateImageDataBeforeSave($data['image_path']);
            }

            $model = $this->_objectManager->create(\UIS\FlashDeals\Model\FlashDeals::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Flashdeals no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            
            if (!empty($this->prepareRelatedProductData())) {
                $data['flashdeal_product_ids'] = $this->prepareRelatedProductData();  
            }

            try {
                //save 

                $model->setData($data);
                $model->save();

                $this->_eventManager->dispatch('uis_flashdeals_flashdeal_save_success', ['flashdeals_id' => $id, 'flashdeals_data' => $model->getData() ]);

                $this->messageManager->addSuccessMessage(__('You saved the Flashdeals.'));
                $this->dataPersistor->clear('uis_flashdeals_flashdeals');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['flashdeals_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {

                $this->messageManager->addExceptionMessage($e) ;
                // $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Flashdeals.'));
            }
        
            $this->dataPersistor->set('uis_flashdeals_flashdeals', $data);
            return $resultRedirect->setPath('*/*/edit', ['flashdeals_id' => $this->getRequest()->getParam('flashdeals_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    
    /**
     * Convert Image Data from array to Json and normalize it   
     *
     * @param array imageData
     * @return json 
     */
    private function updateImageDataBeforeSave($imageData) {
        if ($imageData) {

            return substr(json_encode($imageData), 1, -1);
        }
    }

    /**
     * Return Product entity Ids from Product Listing response
     *
     * @return json relatedPoductData
     */
    private function prepareRelatedProductData() { 
        $relatedPoductData = [];
        if ($this->getRequest()->getParam('uis_flashdeals_related_product_listing')) {
            $productListingData = $this->getRequest()->getParam('uis_flashdeals_related_product_listing');
            foreach ($productListingData as $productData) {
                $relatedPoductData[] = [ 'entity_id' => $productData['entity_id'] ];
            }
        }  
        return json_encode( $relatedPoductData );
    }  
}

