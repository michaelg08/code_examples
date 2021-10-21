<?php
namespace UIS\FlashDeals\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
class LayoutObserver implements ObserverInterface
{
        protected $set_logger;
        public function __construct ( \Psr\Log\LoggerInterface $logger_cons )
	{
            $this->set_logger = $logger_cons;
        }

        public function execute(\Magento\Framework\Event\Observer $observer)
        {
            $get_xml = $observer->getEvent()->getLayout()->getXmlString();

            /*
	    *  $this->set_logger->debug($get_xml);
	    */
            $get_writer = new \Zend\Log\Writer\Stream(BP . '/var/log/layout_block.xml');
            $get_logger = new \Zend\Log\Logger();
            $get_logger->addWriter($get_writer);
            $get_logger->info($get_xml);
            return $this;
        }
}
