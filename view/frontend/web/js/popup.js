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
    'fireworks',
    'bioEp',
    'jquery/ui'
], function ($, firework) {
    'use strict';

    $.widget('mageplaza.betterpopup_block', {
        options: {
            dataPopup: {}
        },

        _create: function () {
            var self = this;

            if (this.options.dataPopup.isScroll) {
                this._scrollToShow();
            } else if (this.options.dataPopup.isExitIntent) {
                $(document).mouseleave(function () {
                    bioEp.init(self.options.dataPopup.popupConfig);
                });
            } else {
                bioEp.init(self.options.dataPopup.popupConfig);
            }

            this._fullScreen();
            this._clickTrigger();
            this._clickClose();
            this._clickSuccess();
        },

        /**
         * Event click float button to show popup
         * @private
         */
        _clickTrigger: function () {
            var self = this;

            $('#mp-better-popup-click-trigger').click(function () {
                var bgEl = $('#bio_ep_bg');
                if (!bgEl.length) {
                    $('body').append("<div id='bio_ep_bg'></div>");
                    self._fullScreen();
                }

                $('#bio_ep').show();
                $('#bio_ep_bg').show();
                $('#bio_ep_close').show();
                bioEp.init(self.options.dataPopup.popupConfig);
                $('#mp-newsletter-error').hide();
                $('#mp-newsletter').css('border-color','#c2c2c2');
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
                $('canvas#screen').hide();
            })
        },

        /**
         * Event click success button
         * @private
         */
        _clickSuccess: function () {
            var self = this,
                bioContent = $('#bio_ep_content'),
                defaultTemplate = $('#mp-popup-default-template').length, //check on default template
                template1 = $('#mp-popup-template1').length, // check on template 1
                template2 = $('#mp-popup-template2').length, // check on template 2
                form = $('#mp-newsletter-validate-detail');

            if (template1 || template2) {
                $('#bio_ep_close').css('color','#000');
            }

            form.submit(function (e) {
                if (form.validation('isValid')) {
                    var email = $("#mp-newsletter").val();
                    var url = form.attr('action');

                    $('.popup-loader').show();
                    e.preventDefault();
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        type: 'POST',
                        data: {email: email},
                        success: function (data) {
                            $.ajax({
                                url: self.options.dataPopup.url,
                                dataType: 'json',
                                cache: false,
                                success: function (result) {
                                    bioContent.empty;
                                    bioContent.html(result.success);
                                    bioContent.trigger('contentUpdated');
                                    if (self.options.dataPopup.isShowFireworks) {
                                        $('canvas#screen').show();
                                        firework(this);
                                    }
                                    if (template1 || template2) {
                                        $('#bio_ep_content').css('color', '#3d3d3e');
                                        $('#mp-coupon-code').css('color', '#3d3d3e');
                                    }
                                }
                            });
                        },
                    });
                } else if (defaultTemplate) {
                    var mgsError = $('#mp-newsletter-error'),
                        btnSubmit = $('.better-popup-btn-submit');

                    btnSubmit.after(mgsError);
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
                    bioEp.init(self.options.dataPopup.popupConfig);
                    $(window).off('scroll');
                }
            });
        },

        _fullScreen: function () {
            if (this.options.dataPopup.fullScreen.isFullScreen) {
                $('#bio_ep_bg').css({'background-color': this.options.dataPopup.fullScreen.bgColor, 'opacity': 1});
            }
        }

    });

    return $.mageplaza.betterpopup_block;
});