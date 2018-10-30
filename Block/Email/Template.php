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

namespace Mageplaza\BetterPopup\Block\Email;

use Mageplaza\BetterPopup\Block\Subscriber;

/**
 * Class Template
 * @package Mageplaza\BetterPopup\Block\Email
 */
class Template extends Subscriber
{
    /**
     * Get list email subscribers in the week
     *
     * @return array
     */
    public function getListEmailSubscriberWeek()
    {
        $listEmail             = [];
        $subscribersCollection = $this->getSubscriberInWeek($this->_helperData->getStoreId());
        foreach ($subscribersCollection as $item) {
            $listEmail[] = $item->getData('subscriber_email');
        }

        return $listEmail;
    }

    /**
     * Get Format Current time (title email)
     *
     * @return false|string
     */
    public function getCurrentTime()
    {
        $date = $this->_getDayDate->gmtDate('Y-M-d');

        return date('d M Y', strtotime($date));
    }
}