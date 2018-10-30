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

namespace Mageplaza\BetterPopup\Block;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Template;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Widget\Block\BlockInterface;
use Mageplaza\BetterPopup\Helper\Data as HelperData;

/**
 * Class Subscriber
 * @package Mageplaza\BetterPopup\Block
 */
class Subscriber extends Template implements BlockInterface
{
    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscriberCollectionFactory;

    /**
     * @var DateTime
     */
    protected $_getDayDate;

    /**
     * @var \Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * Subscriber constructor.
     * @param Template\Context $context
     * @param HelperData $helperData
     * @param CollectionFactory $subscriberCollectionFactory
     * @param DateTime $getDayDate
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        DateTime $getDayDate,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->_helperData                  = $helperData;
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->_getDayDate                  = $getDayDate;
    }

    /**
     * @param $from
     * @param $to
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberCollection($from, $to, $storeId)
    {
        $subscribersCollection = $this->_subscriberCollectionFactory->create()->useOnlySubscribed()
            ->addFieldToFilter('change_status_at', ['from' => $from, 'to' => $to])
            ->addStoreFilter($storeId);

        return $subscribersCollection;
    }

    /**
     * Get Subscriber Collection today
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberToday()
    {
        $form       = $this->_getDayDate->date(null, '0:0:0');
        $to         = $this->_getDayDate->date(null, '23:59:59');
        $collection = $this->getSubscriberCollection($form, $to, $this->_helperData->getStoreId());

        return $collection;
    }

    /**
     * Get Subscriber Collection in a week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberInWeek($storeId = null)
    {
        $now        = date("Y-m-d h:i:s");
        $to         = strtotime('+1 day', strtotime($now));
        $to         = date('Y-m-d h:i:s', $to);
        $from       = strtotime('-7 day', strtotime($now));
        $from       = date('Y-m-d h:i:s', $from);
        $collection = $this->getSubscriberCollection($from, $to, $storeId);

        return $collection;
    }

    /**
     * Get Subscriber Collection in a month
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberInMonth()
    {
        $now        = date("Y-m-d h:i:s");
        $to         = strtotime('+1 day', strtotime($now));
        $to         = date('Y-m-d h:i:s', $to);
        $from       = strtotime('-30 day', strtotime($now));
        $from       = date('Y-m-d h:i:s', $from);
        $collection = $this->getSubscriberCollection($from, $to, $this->_helperData->getStoreId());

        return $collection;
    }

    /**
     * Get Unsubscribers Collection in the week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getUnSubscriberCollection($storeId)
    {
        $to                      = date("Y-m-d h:i:s");
        $from                    = strtotime('-7 day', strtotime($to));
        $from                    = date('Y-m-d h:i:s', $from);
        $unSubscribersCollection = $this->_subscriberCollectionFactory->create()
            ->addFieldToFilter('subscriber_status', \Magento\Newsletter\Model\Subscriber::STATUS_UNSUBSCRIBED)
            ->addFieldToFilter('change_status_at', ['from' => $from, 'to' => $to])
            ->addStoreFilter($storeId);

        return $unSubscribersCollection;
    }
}