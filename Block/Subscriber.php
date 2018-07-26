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

namespace Mageplaza\BetterPopup\Block;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

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
     * Subscriber constructor.
     * @param Template\Context $context
     * @param CollectionFactory $subscriberCollectionFactory
     * @param DateTime $getDayDate
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $subscriberCollectionFactory,
        DateTime $getDayDate,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->_getDayDate = $getDayDate;
    }

    /**
     * Get Subscribers Collection in the week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberCollection($from, $to)
    {
        $subscribersCollection = $this->_subscriberCollectionFactory->create()->useOnlySubscribed()
            ->addFieldToFilter('change_status_at', array('from' => $from, 'to' => $to));

        return $subscribersCollection;
    }

    /**
     * Get Subscriber Collection today
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberToday()
    {
        $form = $this->_getDayDate->date(null, '0:0:0');
        $to = $this->_getDayDate->date(null, '23:59:59');

        $collection = $this->getSubscriberCollection($form, $to);

        return $collection;
    }

    /**
     * Get Subscriber Collection in a week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberInWeek()
    {
        $to = date("Y-m-d h:i:s");
        $from = strtotime('-7 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);
        $collection = $this->getSubscriberCollection($from, $to);

        return $collection;
    }

    /**
     * Get Subscriber Collection in a month
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberInMonth()
    {
        $to = date("Y-m-d h:i:s");
        $from = strtotime('-30 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);
        $collection = $this->getSubscriberCollection($from, $to);

        return $collection;
    }

    /**
     * Get Unsubscribers Collection in the week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getUnSubscriberCollection()
    {
        $to = date("Y-m-d h:i:s");
        $from = strtotime('-7 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);
        $unSubscribersCollection = $this->_subscriberCollectionFactory->create()
            ->addFieldToFilter('subscriber_status', \Magento\Newsletter\Model\Subscriber::STATUS_UNSUBSCRIBED)
            ->addFieldToFilter('change_status_at', array('from' => $from, 'to' => $to));

        return $unSubscribersCollection;
    }

}