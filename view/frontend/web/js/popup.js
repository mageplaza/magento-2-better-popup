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
            this._clickClose();
            this._clickSuccess();
            this._duplicatedPopup();
            // if (this.options.dataPopup.isScroll) {
            //     this._scrollToShow();
            // }
        },

        _clickTrigger: function () {
            $('#mp-better-popup-click-trigger').click(function () {
                var bgEl = $('#bio_ep_bg');
                if (!bgEl.length) {
                    $('body').append("<div id='bio_ep_bg'></div>");
                }

                $('#bio_ep').show();
                $('#bio_ep_bg').show();
                $('#bio_ep_close').show();
            });
        },

        _clickClose: function () {
            $('#bio_ep_close').click(function () {
                $('#bio_ep').hide();
                $('#bio_ep_bg').hide();
            })
        },

        _clickSuccess: function () {
            var self = this,
                bioContent = $('#bio_ep_content');
            $('.better-popup-btn-submit').click(function () {

                $.ajax({
                    url: self.options.dataPopup.url,
                    dataType: 'json',
                    cache: false,
                    success: function (result) {
                        bioContent.empty;
                        bioContent.html(result.success);
                        bioContent.trigger('contentUpdated');
                    }
                });
            });
        },

        _scrollToShow: function () {
            var self = this;

            $(window).scroll(function () {
                var scrollTop = $(window).scrollTop(),
                    docHeight = $(document).height(),
                    winHeight = $(window).height(),
                    scrollPercent = (scrollTop) / (docHeight - winHeight),
                    optionScroll = self.options.dataPopup.percentage / 100;

                if (scrollPercent > optionScroll) {
                    $('#bio_ep').show();
                    $('#bio_ep_bg').show();
                    $(window).off('scroll');
                }
            });
        },

        _duplicatedPopup: function () {
            var popupElement = $('.mageplaza-betterpopup-block');
            if (popupElement.length > 1) {
                $('.mageplaza-betterpopup-block:gt(0)').remove();
            }
        }
    });

    return $.mageplaza.betterpopup_block;
});