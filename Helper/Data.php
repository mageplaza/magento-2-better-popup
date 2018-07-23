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

use Mageplaza\Core\Helper\AbstractData as AbstractHelper;

/**
 * Class Data
 * @package Mageplaza\BetterPopup\Helper
 */
class Data extends AbstractHelper
{
	const CONFIG_MODULE_PATH = 'betterpopup';

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhatToShowConfig($code, $storeId = null)
	{
		return $this->getModuleConfig('what_to_show/' . $code, $storeId);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhereToShowConfig($code, $storeId = null)
	{
		return $this->getModuleConfig('where_to_show/' . $code, $storeId);
	}

	/**
	 * @param $code
	 * @param null $storeId
	 * @return array|mixed
	 */
	public function getWhenToShowConfig($code, $storeId = null)
	{
		return $this->getModuleConfig('when_to_show/' . $code, $storeId);
	}

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getSendEmailConfig($code, $storeId = null)
    {
        return $this->getModuleConfig('send_email/' . $code, $storeId);
    }

    /**
     * Is Send Email Config
     *
     * @return mixed
     */
	public function isSendEmail()
    {
        return $this->getSendEmailConfig('isSendEmail');
    }

    /**
     * Get Email is received email
     *
     * @return mixed
     */
    public function getToEmail()
    {
        return $this->getSendEmailConfig('to');
    }

}