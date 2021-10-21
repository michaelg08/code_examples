<?php
declare(strict_types=1);

namespace UIS\FlashDeals\Controller\Adminhtml\FlashDeals;

class Edit extends \UIS\FlashDeals\Controller\Adminhtml\FlashDeals
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('flashdeals_id');
        $model = $this->_objectManager->create(\UIS\FlashDeals\Model\FlashDeals::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Flashdeals no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('uis_flashdeals_flashdeals', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Flashdeals') : __('New Flashdeals'),
            $id ? __('Edit Flashdeals') : __('New Flashdeals')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Flashdeals'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Flashdeals %1', $model->getId()) : __('New Flashdeals'));
        return $resultPage;
    }
}

