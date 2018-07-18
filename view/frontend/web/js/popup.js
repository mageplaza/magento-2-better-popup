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
    'bioEp',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('mageplaza.betterpopup_block', {
        options: {
            dataPopup: {}
        },

        _create: function () {
            bioEp.init(this.options.dataPopup.popupConfig);
            this._clickTrigger();
            this._clickClose();
            this._clickSuccess();
            // if (this.options.dataPopup.isScroll) {
            //     this._scrollToShow();
            // }
        },

        /**
         * Event click float button to show popup
         * @private
         */
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

        /**
         * Event click close popup button
         * @private
         */
        _clickClose: function () {
            $('#bio_ep_close').click(function () {
                $('#bio_ep').hide();
                $('#bio_ep_bg').hide();
                $('.btn-copy').text('Copy');
            })
        },

        /**
         * Event click success button
         * @private
         */
        _clickSuccess: function () {
            var self = this,
                bioContent = $('#bio_ep_content');

            $('.better-popup-btn-submit').click(function () {
                var userEmail = $(".better-popup-input-email").val();

                if (self.validateMailAddress(userEmail)) {
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
                } else {
                    if(userEmail) {
                        $("#status").text("Please enter a valid email address (Ex: johndoe@domain.com).");
                    } else {
                        $("#status").text("This is a required field.");
                    }
                }
            });
        },

        /**
         * Scroll to show popup
         * @private
         */
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

        /**
         * Check format email
         *
         * @param userEmail
         * @returns {boolean}
         */
        validateMailAddress: function(userEmail) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            return re.test(userEmail);
        }
    });

    return $.mageplaza.betterpopup_block;
});