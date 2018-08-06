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

namespace Mageplaza\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class PageToShow
 * @package Mageplaza\BetterPopup\Model\Config\Source
 */
class PageToShow implements ArrayInterface
{
    const SPECIFIC_PAGES  = 1;
    const ALL_PAGES       = 2;
    const MANUALLY_INSERT = 3;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SPECIFIC_PAGES, 'label' => __('Specific pages')],
            ['value' => self::ALL_PAGES, 'label' => __('All Pages')],
            ['value' => self::MANUALLY_INSERT, 'label' => __('Manually Insert')],
        ];
    }
}
