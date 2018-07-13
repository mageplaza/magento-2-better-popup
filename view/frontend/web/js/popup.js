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

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('mageplaza.betterpopup_block', {
        options: {
            dataPopup: {}
        },

        _create: function () {
            this._clickTrigger();
            this._scrollToShow();
        },

        _clickTrigger: function () {
            $('#mp-better-popup-click-trigger').click(function () {
                $('#bio_ep').show();
                $('#bio_ep_bg').show();
            });
        },

        _scrollToShow: function () {
            var self = this;
            $(window).scroll(function(){
                var scrollTop = $(window).scrollTop();
                var docHeight = $(document).height();
                var winHeight = $(window).height();
                var scrollPercent = (scrollTop) / (docHeight - winHeight);
                var optionScroll = self.options.dataPopup.percentage/100;

                // alert(this.options.percentage);

                if(scrollPercent > optionScroll) {
                    $('#bio_ep').show();
                    $('#bio_ep_bg').show();
                    $(window).off('scroll');
                }
            });
        }

    });

    return $.mageplaza.betterpopup_block;
});