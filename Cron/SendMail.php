<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_BetterPopup
 * @copyright   Copyright (c) Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BetterPopup\Cron;

use Mageplaza\BetterPopup\Helper\Data;
use Mageplaza\BetterPopup\Block\Email\Template;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SendMail
 * @package Mageplaza\BetterPopup\Cron
 */
class SendMail
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Mageplaza\BetterPopup\Block\Email\Template
     */
    protected $_template;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * SendMail constructor.
     * @param Data $helperData
     * @param Template $template
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data $helperData,
        Template $template,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    )
    {
        $this->_helperData = $helperData;
        $this->_template = $template;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute()
    {
        foreach ($this->_storeManager->getStores() as $store) {
            if ($this->_helperData->isEnabled($store->getId()) && $this->_helperData->isSendEmail($store->getId())) {
                $this->sendMail($store->getId());
            }
        }
    }

    /**
     * Send Email
     *
     * @param $storeId
     */
    public function sendMail($storeId)
    {
        $subscriber = $this->_template->getSubscriberCollection()->getSize();
        $unSubscriber = $this->_template->getunSubscriberCollection()->getSize();
        $currentTime = $this->_template->getCurrentTime();
        $toEmail = $this->_helperData->getToEmail();

        $vars = [
            'mp_subscriber' => $subscriber,
            'mp_unSubscriber' => $unSubscriber,
            'currentTime' => $currentTime
        ];
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('mageplaza_betterpopup_template')
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId
                ]
            )
            ->setFrom('general')
            ->addTo($toEmail)
            ->setTemplateVars($vars)
            ->getTransport();

        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}