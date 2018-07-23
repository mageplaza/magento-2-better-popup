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

namespace Mageplaza\BetterPopup\Controller\Send;

use Psr\Log\LoggerInterface;

class SendMail extends \Magento\Framework\App\Action\Action
{
    /**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    protected $_helperData;

    protected $template;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mageplaza\BetterPopup\Helper\Data $helperData,
        \Mageplaza\BetterPopup\Block\Email\Template $template,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper,
        LoggerInterface $logger
    )
    {
        parent::__construct($context);

        $this->_helperData = $helperData;
        $this->template = $template;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->logger = $logger;
    }

    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $subscriber = $this->template->getSubscriberCollection()->getSize();
        $unSubscriber = $this->template->getunSubscriberCollection()->getSize();
        $currentTime = $this->template->getCurrentTime();
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
                    'store' => $this->storeManager->getStore()->getId()
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