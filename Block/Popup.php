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
use Mageplaza\BetterPopup\Model\Config\Source\Appear;
use Mageplaza\BetterPopup\Model\Config\Source\Responsive;
use Mageplaza\BetterPopup\Model\Config\Source\PageToShow;

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
	 * Get Config Popup Appear
	 *
	 * @return array|mixed
	 */
	public function getPopupAppear()
	{
		return $this->_helperData->getWhenToShowConfig('popup_appear');
	}

	/**
	 * Get time delay to show popup
	 *
	 * @return array|int|mixed
	 */
	public function getDelayConfig()
	{
		if ($this->getPopupAppear() == Appear::AFTER_X_SECONDS) {
			return $this->_helperData->getWhenToShowConfig('delay');
		}

		return 0;
	}

	/**
	 * is Show on Delay
	 *
	 * @return string
	 */
	public function isShowOnDelay()
	{
		if ($this->getPopupAppear() == Appear::EXIT_INTENT) {
			return 'false';
		}

		return 'true';
	}

	/**
	 * Get Popup show again after (days)
	 *
	 * @return array|mixed
	 */
	public function getCookieConfig()
	{
		$cookieDays = $this->_helperData->getWhenToShowConfig('cookieExp');

		return ($cookieDays != null) ? $cookieDays : '30';
	}

	/**
	 * Get Percentage scroll down to show Popup
	 *
	 * @return array|mixed
	 */
	public function getPercentageScroll()
	{
		return $this->_helperData->getWhenToShowConfig('after_scroll');
	}

	/**
	 * Get Css for Popups
	 *
	 * @return string
	 */
	public function getCss()
	{
		$css = "#bio_ep {background-color:" . $this->getBackGroundColor() . ";}";

		if ($this->getResponsive() == Responsive::FULLSCREEN_POPUP) {
			$css .= "#bio_ep_bg {background-color:" . $this->getBackGroundColor() . "; opacity: 1; }" . "#bio_ep {box-shadow: none;}";
		}

		return $css;
	}

	/**
	 * Check pages are showed Popup
	 *
	 * @return bool
	 */
	public function checkIncludePages()
	{
		if ($this->_helperData->getWhereToShowConfig('which_page_to_show') == PageToShow::SPECIFIC_PAGES) {
			$fullActionName = $this->getRequest()->getFullActionName();
			$includePages   = explode(',', $this->_helperData->getWhereToShowConfig('include_pages'));

			return in_array($fullActionName, $includePages);
		}

		return true;
	}

	/**
	 * Get All Config of Bio_ep
	 *
	 * @return string
	 */
	public function BioEpConfig()
	{
		return
			'width:' . $this->getWidthPopup() . ',' .
			'height:' . $this->getHeightPopup() . ',' .
			"css:'" . $this->getCss() . "'," .
			'cookieExp:' . $this->getCookieConfig() . ',' .
			'delay:' . $this->getDelayConfig() . ',' .
			'showOnDelay:' . $this->isShowOnDelay();
	}

	public function getDataPopup()
	{
		$data = [
			'isScroll'   => $this->getPopupAppear() == Appear::AFTER_SCROLL_DOWN ? true : false,
			'percentage' => $this->getPercentageScroll()
		];

		return HelperData::jsonEncode($data);
	}

}