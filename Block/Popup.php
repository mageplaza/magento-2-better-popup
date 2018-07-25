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
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

/**
 * Class Popup
 * @package Mageplaza\BetterPopup\Block
 */
class Popup extends AbstractProduct implements BlockInterface
{
    const LIMIT_TIME = 10000000;
    /**
     * @var \Mageplaza\BetterPopup\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscriberCollectionFactory;

    /**
     * Popup constructor.
     * @param Context $context
     * @param HelperData $helperData
     * @param CollectionFactory $subscriberCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;

        parent::__construct($context, $data);
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
     * Check FullScreen option
     *
     * @return bool
     */
    public function isFullScreen()
    {
        if ($this->_helperData->getWhatToShowConfig('responsive') == Responsive::FULLSCREEN_POPUP) {
            return true;
        }
        return false;
    }

    /**
     * Check show fireworks config
     *
     * @return bool
     */
    public function isShowFireworks()
    {
        if ($this->_helperData->getWhatToShowConfig('popup_success/enabled_fireworks')) {
            return true;
        }

        return false;
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
        } else if ($this->getPopupAppear() == Appear::AFTER_SCROLL_DOWN) {
            return self::LIMIT_TIME;
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
            return false;
        }

        return true;
    }

    /**
     * Get Popup show again after (days)
     *
     * @return array|mixed
     */
    public function getCookieConfig()
    {
        $cookieDays = (int)$this->_helperData->getWhenToShowConfig('cookieExp');

        return ($cookieDays !== null) ? $cookieDays : 30;
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
     * Get Html Content popup
     *
     * @return mixed
     */
    public function getPopupContent()
    {
        $htmlConfig = $this->_helperData->getWhatToShowConfig('html_content');

        $search = ['{{form_url}}', '{{url_loader}}'];
        $replace = [$this->getFormActionUrl(), $this->getViewFileUrl('images/loader-1.gif')];

        $html = str_replace($search, $replace, $htmlConfig);

        return $html;
    }

    /**
     * Check include pages are show Popup
     *
     * @return bool
     */
    public function checkIncludePages()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        $arrayPages = explode("\n", $this->_helperData->getWhereToShowConfig('include_pages'));
        $includePages = array_map('trim', $arrayPages);

        return in_array($fullActionName, $includePages);
    }

    /**
     * Check include paths to show popup
     *
     * @return bool
     */
    public function checkIncludePaths()
    {
        $currentPath = $this->getRequest()->getRequestUri();
        $pathsConfig = $this->_helperData->getWhereToShowConfig('include_pages_with_url');

        if ($pathsConfig) {
            $arrayPaths = explode("\n", $pathsConfig);
            $pathsUrl = array_map('trim', $arrayPaths);
            foreach ($pathsUrl as $path) {
                if (strpos($currentPath, $path) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check Exclude page to hide popup
     *
     * @return bool
     */
    public function checkExcludePages()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        $arrayPages = explode("\n", $this->_helperData->getWhereToShowConfig('exclude_pages'));
        $includePages = array_map('trim', $arrayPages);

        return !in_array($fullActionName, $includePages);
    }

    /**
     * Check Exclude Paths to hide popup
     *
     * @return bool
     */
    public function checkExcludePaths()
    {
        $currentPath = $this->getRequest()->getRequestUri();
        $pathsConfig = $this->_helperData->getWhereToShowConfig('exclude_pages_with_url');

        if ($pathsConfig) {
            $arrayPaths = explode("\n", $pathsConfig);
            $pathsUrl = array_map('trim', $arrayPaths);

            foreach ($pathsUrl as $path) {
                if (strpos($currentPath, $path) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check Include (page & path)
     *
     * @return bool
     */
    public function checkInclude()
    {
        return ($this->checkIncludePages() || $this->checkIncludePaths());
    }

    /**
     * Check Exclude (page & path)
     *
     * @return bool
     */
    public function checkExclude()
    {
        return ($this->checkExcludePages() && $this->checkExcludePaths());
    }

    /**
     * check Manually Insert Config
     *
     * @return bool
     */
    public function isManuallyInsert()
    {
        if ($this->_helperData->isEnabled()) {
            if ($this->_helperData->getWhereToShowConfig('which_page_to_show') == PageToShow::MANUALLY_INSERT && $this->checkExclude()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check Pages to show popup
     *
     * @return bool
     */
    public function checkPagesToShow()
    {
        if ($this->_helperData->isEnabled()) {
            $config = $this->_helperData->getWhereToShowConfig('which_page_to_show');

            switch ($config) {
                case PageToShow::SPECIFIC_PAGES :
                    return ($this->checkInclude() && $this->checkExclude());
                case PageToShow::ALL_PAGES :
                    return $this->checkExclude();
                case PageToShow::MANUALLY_INSERT :
                    return false;
            }
        }

        return false;
    }

    /**
     * Get Ajax Data
     *
     * @return string
     */
    public function getAjaxData()
    {
        $params = [
            'url' => $this->getUrl('betterpopup/ajax/success'),
            'isScroll' => $this->getPopupAppear() == Appear::AFTER_SCROLL_DOWN,
            'percentage' => $this->getPercentageScroll(),
            'fullScreen' => [
                'isFullScreen' => $this->isFullScreen(),
                'bgColor' => $this->getBackGroundColor()
            ],
            'isShowFireworks' => $this->isShowFireworks(),
            'popupConfig' => [
                'width' => $this->getWidthPopup(),
                'height' => $this->getHeightPopup(),
                'cookieExp' => $this->getCookieConfig(),
                'delay' => $this->getDelayConfig(),
                'showOnDelay' => $this->isShowOnDelay(),
            ]
        ];

        return HelperData::jsonEncode($params);
    }

    /**
     * Get Url NewAction Newsletter
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('newsletter/subscriber/new', ['_secure' => true]);
    }

}