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

namespace Mageplaza\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class IncludePages
 * @package Mageplaza\BetterPopup\Model\Config\Source
 */
class IncludePages implements ArrayInterface
{
	const CATALOG_PRODUCT_VIEW = 1;
	const HOME_INDEX_INDEX = 2;
	const MAGEPLAZA_BLOG_INDEX = 3;
	const MAGEPLAZA_BLOG_POSTS = 4;

	/**
	 * Return array of options as value-label pairs
	 *
	 * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
	 */
	public function toOptionArray()
	{
		return [
			['value' => self::CATALOG_PRODUCT_VIEW, 'label' => __('Catalog_Product_View')],
			['value' => self::HOME_INDEX_INDEX, 'label' => __('Home_Index_Index')],
			['value' => self::MAGEPLAZA_BLOG_INDEX, 'label' => __('Mageplaza_Blog_Index')],
			['value' => self::MAGEPLAZA_BLOG_POSTS, 'label' => __('Mageplaza_Blog_Posts')],
		];
	}
}
