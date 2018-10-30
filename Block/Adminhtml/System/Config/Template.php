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

namespace Mageplaza\BetterPopup\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Mageplaza\BetterPopup\Helper\Data;

/**
 * Class Provider
 * @package Mageplaza\Smtp\Block\Adminhtml\System\Config
 */
class Template extends Field
{
    /**
     * Template
     */
    protected $_template = 'Mageplaza_BetterPopup::system/config/template.phtml';

    /**
     * @var \Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * Template constructor.
     * @param Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helperData,
        array $data = []
    )
    {
        $this->_helperData = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel  = !empty($originalData['button_label']) ? $originalData['button_label'] : '';
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id'      => $element->getHtmlId(),
                'mp_template'  => $this->getOptionTemplate(),
                'data_info'    => json_encode($this->getOptionTemplate())
            ]
        );

        return $this->_toHtml();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function getOptionTemplate()
    {
        $options = [
            [
                'label'       => __('Default Template'),
                'popupHtml'   => $this->_helperData->getDefaultTemplateHtml('template3/popup'),
                'successHtml' => $this->_helperData->getDefaultTemplateHtml('success'),
                'background'  => '#3d9bc7',
                'textColor'   => '#000000',
                'width'       => '800',
                'height'      => '321'
            ],
            [
                'label'       => __('Template 1'),
                'popupHtml'   => $this->_helperData->getDefaultTemplateHtml('template4/popup'),
                'successHtml' => $this->_helperData->getDefaultTemplateHtml('success'),
                'background'  => '#fbf5ee',
                'textColor'   => '#000000',
                'width'       => '605',
                'height'      => '330'
            ],
            [
                'label'       => __('Template 2'),
                'popupHtml'   => $this->_helperData->getDefaultTemplateHtml('template5/popup'),
                'successHtml' => $this->_helperData->getDefaultTemplateHtml('success'),
                'background'  => '#31B0D5',
                'textColor'   => '#000000',
                'width'       => '359',
                'height'      => '260'
            ],
            [
                'label'       => __('Template 3'),
                'popupHtml'   => $this->_helperData->getDefaultTemplateHtml('template6/popup'),
                'successHtml' => $this->_helperData->getDefaultTemplateHtml('success'),
                'background'  => '#31B0D5',
                'textColor'   => '#000000',
                'width'       => '800',
                'height'      => '250'
            ]
        ];

        return $options;
    }
}