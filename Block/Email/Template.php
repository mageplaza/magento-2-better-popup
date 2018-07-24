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

namespace Mageplaza\BetterPopup\Block\Email;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;
use Magento\Backend\Block\Template as AbstractTemplate;
use Magento\Catalog\Block\Product\Context;
use Mageplaza\BetterPopup\Helper\Data as HelperData;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class Template extends AbstractTemplate implements BlockInterface
{
    /**
     * @var \Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscriberCollectionFactory;

    protected $date;

    protected $_backendUrl;

    protected $urlBuilder;

    protected $backenHelper;

    public function __construct(
        AbstractTemplate\Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Backend\Helper\Data $backenHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_helperData = $helperData;
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->date = $date;
        $this->_backendUrl = $backendUrl;
        $this->urlBuilder = $urlBuilder;
        $this->backenHelper = $backenHelper;
    }

    /**
     * Get Subscribers Collection in the week
     *
     * @return \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    public function getSubscriberCollection()
    {
        $to = date("Y-m-d h:i:s");
        $from = strtotime('-7 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);
        $subscribersCollection = $this->_subscriberCollectionFactory->create()->useOnlySubscribed()
            ->addFieldToFilter('change_status_at', array('from' => $from, 'to' => $to));

        return $subscribersCollection;
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

    /**
     * Get list email subscribers in the week
     *
     * @return array
     */
    public function getListEmailSubscriber()
    {
        $listEmail = [];
        $subscribersCollection = $this->getSubscriberCollection();
        foreach ($subscribersCollection as $item) {
            $listEmail[] = $item->getData('subscriber_email');
        }

        return $listEmail;
    }

    public function getCurrentTime(){
        $date = $this->date->gmtDate('Y-m-d');

        return date('F d, Y', strtotime($date));
    }

    public function getFormActionUrl()
    {
        $url = $this->getUrl('newsletter/subscriber/index');

        return $url;
    }

}