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

/**
 * Class Success
 * @package Mageplaza\BetterPopup\Block
 */
class Success extends Popup
{
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_BetterPopup::popup/success.phtml';

    /**
     * Get Coupon code
     *
     * @return array|mixed
     */
    public function getCouponCode()
    {
        return $this->_helperData->getWhatToShowConfig('popup_success/coupon_code');
    }

    /**
     * Get Html Popup success
     *
     * @return mixed
     */
    public function getPopupSuccessContent()
    {
        $htmlConfig = $this->_helperData->getWhatToShowConfig('popup_success/html_success_content');
        $html       = str_replace('{{coupon_code}}', $this->getCouponCode(), $htmlConfig);

        return $html;
    }
}