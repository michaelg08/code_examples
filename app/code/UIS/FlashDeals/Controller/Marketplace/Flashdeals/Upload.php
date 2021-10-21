<?php

declare(strict_types=1);

namespace UIS\FlashDeals\Controller\Marketplace\FlashDeals;

use \Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\Exception\LocalizedException;

class Upload extends \Magento\Framework\App\Action\Action
{
    protected $dataPersistor;
    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    protected $_uishelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \UIS\FlashDeals\Helper\Data $uishelper
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_uishelper = $uishelper;

        parent::__construct($context);
    }

    /**
     * Upload action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        //$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getPostValue();
        $result = $data;
        if ($data) {
            try {

                $folderName = $this->_uishelper->getBannerUploadFolder();
                $target = $this->_mediaDirectory->getAbsolutePath($folderName);

                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image_path']);

                /** Allowed extension types */
                $uploader->setAllowedExtensions(['jpg', 'png', 'bmp', 'gif', 'apng', 'svg']);

                /** rename file name if already exists */
                $uploader->setAllowRenameFiles(true);

                /** upload file in folder "mycustomfolder" */
                $result = $uploader->save($target);

                if ($result['file']) {
                    $this->messageManager->addSuccess(__('File has been successfully uploaded'));
                }
            } catch (\Exception $e) {
                //$this->messageManager->addError($e->getMessage());    
                //$this->messageManager->addError($e);    
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Flashdeal Banner.'));
            }
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
