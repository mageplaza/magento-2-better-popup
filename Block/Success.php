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

use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Mageplaza\BetterPopup\Helper\Data as HelperData;
use Mageplaza\BetterPopup\Model\Generate;

/**
 * Class Success
 * @package Mageplaza\BetterPopup\Block
 */
class Success extends Popup
{

    /**
     * @var Generate
     */
    protected $generate;

    /**
     * Success constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param CollectionFactory $subscriberCollectionFactory
     * @param Generate $generate
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        Generate $generate,
        array $data = []
    ) {
        parent::__construct($context, $helperData, $subscriberCollectionFactory, $data);
        $this->generate = $generate;
    }

    /**
     * @var string
     */
    protected $_template = 'Mageplaza_BetterPopup::popup/success.phtml';

    /**
     * @return string|string[]|null
     * @throws LocalizedException
     */
    public function getCouponCode()
    {
        $couponCode = '';
        if (!$this->_helperData->getWhatToShowConfig('popup_success/enable_coupon')) {
            return $couponCode;
        }
        $data = [
            'rule_id' => $this->_helperData->getWhatToShowConfig('popup_success/rule_id'),
            'coupon_pattern' => $this->_helperData->getWhatToShowConfig('popup_success/coupon_pattern'),
        ];

        return $this->generate->generateCoupon($data);
    }

    /**
     * Get Html Popup success
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getPopupSuccessContent()
    {
        $htmlConfig = $this->_helperData->getWhatToShowConfig('popup_success/html_success_content');

        return str_replace('{{coupon_code}}', $this->getCouponCode(), $htmlConfig);
    }
}
