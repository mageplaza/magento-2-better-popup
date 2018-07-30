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

namespace Mageplaza\BetterPopup\Model\Config\Backend;


class Validate extends \Magento\Config\Model\Config\Backend\Serialized
{
    /**
     * Check value not null Exclude and Include
     *
     * @return \Magento\Config\Model\Config\Backend\Serialized
     * @throws \Exception
     */
    public function beforeSave()
    {
        $pageToShow = $this->getData('fieldset_data')['which_page_to_show'];
        $exPage = $this->getData('fieldset_data')['exclude_pages'];
        $exPageUrl = $this->getData('fieldset_data')['exclude_pages_with_url'];

        if ($exPage || $exPageUrl) {
            $inPage = $this->getData('fieldset_data')['include_pages'];
            $inPageUrl = $this->getData('fieldset_data')['include_pages_with_url'];

            if ($pageToShow != 1) {
                return parent::beforeSave();
            } else if (!($inPage || $inPageUrl)) {
                throw new \Exception('Please enter the value into one of the following boxes: Include page(s) and Include Page(s) with URL contains.');
            }

            return parent::beforeSave();
        } else {
            throw new \Exception('Please enter the value into one of the following boxes: Exclude page(s) and Exclude Page(s) with URL contains.');
        }

    }
}