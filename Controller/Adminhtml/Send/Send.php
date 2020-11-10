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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BetterPopup\Controller\Adminhtml\Send;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\BetterPopup\Block\Email\Template;
use Mageplaza\BetterPopup\Helper\Data as HelperData;
use Psr\Log\LoggerInterface;

/**
 * Class Send
 * @package Mageplaza\BetterPopup\Controller\Adminhtml\Send
 */
class Send extends Action
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var /Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * @var Template
     */
    protected $_template;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * Send constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param Template $template
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        Template $template,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->_helperData = $helperData;
        $this->_template = $template;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $result['status'] = false;
        $toEmail = $this->_helperData->getToEmail();

        if ($toEmail) {
            try {
                foreach ($this->_storeManager->getStores() as $store) {
                    $isSendMail = $this->_helperData->getSendEmailConfig('isSendEmail', $store->getId());
                    $isEnable = $this->_helperData->isEnabled($store->getId());
                    if ($isEnable && $isSendMail) {
                        $this->sendMail($store);
                    }
                }

                $result['status'] = true;
                $result['content'] = __('Sent successfully! Please check your email box.');
            } catch (Exception $e) {
                $result['content'] = __('There is an error occurred while sending email. Please try again later.');
                $this->logger->critical($e);
            }
        } else {
            $result['status'] = false;
            $result['content'] = __('Please enter email and save config');
        }

        return $this->getResponse()->representJson(HelperData::jsonEncode($result));
    }

    /**
     * Send Mail
     *
     * @param $store
     *
     * @return |null
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendMail($store)
    {
        $toEmail = $this->_helperData->getToEmail();
        if (!$toEmail) {
            return null;
        }

        $subscriber = $this->_template->getSubscriberInWeek($store->getId())->getSize();
        $unSubscriber = $this->_template->getunSubscriberCollection($store->getId())->getSize();
        $currentTime = $this->_template->getCurrentTime();
        $store_name = $store->getName();

        $vars = [
            'mp_subscriber' => $subscriber,
            'mp_unSubscriber' => $unSubscriber,
            'currentTime' => $currentTime,
            'store_name' => $store_name
        ];
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('mageplaza_betterpopup_template')
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $store->getId()
            ])
            ->setFrom('general')
            ->addTo($toEmail)
            ->setTemplateVars($vars)
            ->getTransport();

        try {
            $transport->sendMessage();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
