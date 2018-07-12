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
	public function getBackGroundColor(){
		return $this->_helperData->getWhatToShowConfig('background_color');
	}


}