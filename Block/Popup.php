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

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Product\Context;
use Mageplaza\BetterPopup\Helper\Data as HelperData;
use Mageplaza\BetterPopup\Model\Config\Source\Responsive;

/**
 * Class Popup
 * @package Mageplaza\BetterPopup\Block
 */
class Popup extends AbstractProduct implements BlockInterface
{
	/**
	 * @var \Mageplaza\BetterPopup\Helper\Data
	 */
	protected $_helperData;

	/**
	 * Popup constructor.
	 * @param \Mageplaza\BetterPopup\Helper\Data $helperData
	 * @param \Magento\Catalog\Block\Product\Context $context
	 * @param array $data
	 */
	public function __construct(
		HelperData $helperData,
		Context $context,
		array $data = []
	)
	{
		parent::__construct($context, $data);

		$this->_helperData = $helperData;
	}

	/**
	 * Get Responsive Config
	 *
	 * @return array|mixed
	 */
	public function getResponsive()
	{
		return $this->_helperData->getWhatToShowConfig('responsive');
	}

	/**
	 * Get Width Popup Config
	 *
	 * @return array|mixed
	 */
	public function getWidthPopup()
	{
		return $this->_helperData->getWhatToShowConfig('width');
	}

	/**
	 * Get Height Popup Config
	 *
	 * @return array|mixed
	 */
	public function getHeightPopup()
	{
		return $this->_helperData->getWhatToShowConfig('height');
	}

	/**
	 * Get Background Color Popup
	 *
	 * @return array|mixed
	 */
	public function getBackGroundColor()
	{
		return $this->_helperData->getWhatToShowConfig('background_color');
	}

	/**
	 * Get Text Color in Popup
	 *
	 * @return array|mixed
	 */
	public function getTextColor()
	{
		return $this->_helperData->getWhatToShowConfig('text_color');
	}

	/**
	 * Is Enable Show Float Button
	 *
	 * @return array|mixed
	 */
	public function isShowFloatButton()
	{
		return $this->_helperData->getWhenToShowConfig('show_float_button');
	}

	/**
	 * Get Location Float Button
	 *
	 * @return array|mixed
	 */
	public function getLocationFloatButton()
	{
		return $this->_helperData->getWhenToShowConfig('float_button_direction');
	}

	/**
	 * Get Css for Popups
	 *
	 * @return string
	 */
	public function getCss()
	{
		$css = '';

		if ($this->getResponsive() == Responsive::FULLSCREEN_POPUP) {
			$css .= "#bio_ep_bg {background-color:" . $this->getBackGroundColor() . "; opacity: 1; }" . "#bio_ep {box-shadow: none;}";
		}

		return $css;
	}

}