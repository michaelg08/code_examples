<?php

namespace UIS\FlashDeals\Controller\Marketplace\Flashdeals;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Controller\ResultFactory;
use \UIS\FlashDeals\Model\Upload\ImageFileUploader;

class Uploadimage extends \Magento\Framework\App\Action\Action
{

    /**
     * @var ImageFileUploader
     */
    private $imageFileUploader;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    /**
     * @param Context $context
     * @param ImageFileUploader $imageFileUploader
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ImageFileUploader $imageFileUploader

    ) {
        $this->storeManager = $storeManager;
        $this->imageFileUploader = $imageFileUploader;
        parent::__construct($context);
    }

    /**
     * Image upload action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        try {

            $result = $this->imageFileUploader->saveImageToMediaFolder('image_path');
        } catch (\Exception $e) {

            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
