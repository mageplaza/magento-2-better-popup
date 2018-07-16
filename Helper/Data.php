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

namespace Mageplaza\BetterPopup\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData as AbstractHelper;

/**
 * Class Data
 * @package Mageplaza\BetterPopup\Helper
 */
class Data extends AbstractHelper
{
	const CONFIG_MODULE_PATH = 'betterpopup';

	/**
	 * Data constructor.
	 * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 */
	public function __construct(
		Context $context,
		ObjectManagerInterface $objectManager,
		StoreManagerInterface $storeManager
	)
	{
		parent::__construct($context, $objectManager, $storeManager);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getPopupConfig($code, $storeId = null)
	{
		$code = ($code !== '') ? '/' . $code : '';

		return $this->getConfigValue(self::CONFIG_MODULE_PATH . $code, $storeId);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhatToShowConfig($code, $storeId = null)
	{
		return $this->getPopupConfig('what_to_show/' . $code, $storeId);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhereToShowConfig($code, $storeId = null)
	{
		return $this->getPopupConfig('where_to_show/' . $code, $storeId);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhenToShowConfig($code, $storeId = null)
	{
		return $this->getPopupConfig('when_to_show/' . $code, $storeId);
	}

}