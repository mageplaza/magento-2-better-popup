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

namespace Mageplaza\BetterPopup\Block\Adminhtml\System;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class FeatureDisplay
 * @package Mageplaza\BetterPopup\Block\Adminhtml\System
 */
class FeatureDisplay extends Field
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div class="control-value" style="padding-top: 8px">';
        $html .= '<span><p>' . __('Use the following code to show the popup block in any place you want') . '</p></span>';

        $html .= '<strong>' . __('CMS Page/Static Block') . '</strong><br />';
        $html .= '<span style="font-size: 10px"><pre style="background-color: #f5f5dc"><code>{{block class="Mageplaza\BetterPopup\Block\Popup" template="Mageplaza_BetterPopup::insertpopup.phtml"}}</code></pre></span>';

        $html .= '<strong>' . __('Template .phtml file') . '</strong><br />';
        $html .= '<span style="font-size: 10px"><pre style="background-color: #f5f5dc"><code>' . $this->_escaper->escapeHtml('<?php echo $block->getLayout()->createBlock("Mageplaza\BetterPopup\Block\Popup")->setTemplate("insertpopup.phtml")->toHtml();?>') . '</code></pre></span>';

        $html .= '<strong>' . __('Layout file') . '</strong><br />';
        $html .= '<span style="font-size: 10px"><pre style="background-color: #f5f5dc"><code>' . $this->_escaper->escapeHtml('<block class="Mageplaza\BetterPopup\Block\Popup" template="Mageplaza_BetterPopup::insertpopup.phtml" name="mp_betterpopup_insert" />') . '</code></pre></span>';

        $html .= '</div>';

        return $html;
    }
}
