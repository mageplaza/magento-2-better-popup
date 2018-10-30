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

namespace Mageplaza\BetterPopup\Cron;

use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\BetterPopup\Controller\Adminhtml\Send\Send;
use Mageplaza\BetterPopup\Helper\Data;

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
     * @var \Mageplaza\BetterPopup\Controller\Adminhtml\Send\Send
     */
    protected $_send;

    /**
     * SendMail constructor.
     * @param Data $helperData
     * @param Send $send
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $helperData,
        Send $send,
        StoreManagerInterface $storeManager
    )
    {
        $this->_helperData   = $helperData;
        $this->_send         = $send;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return void
     */
    public function execute()
    {
        foreach ($this->_storeManager->getStores() as $store) {
            if ($this->_helperData->isEnabled($store->getId()) && $this->_helperData->isSendEmail($store->getId())) {
                $this->_send->sendMail($store);
            }
        }
    }
}