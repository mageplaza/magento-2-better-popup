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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
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
            this._showPopup();
            this._clickTrigger();
            this._clickClose();
            this._clickSuccess();
            if (this._checkUpdateTemplate()) {
                $('#mp-powered').hide();
                if ($('#mp-popup-template5').length) {
                    $('#bio_ep_close').css({'top': '-100px'});
                    $('#bio_ep_close img').attr('src', this.options.dataPopup.srcCloseIconWhite);
                }
            }

        },

        _showPopup: function () {
            var self = this,
                popupElem = $('.mageplaza-betterpopup-block').length,
                triggerElem = $('.mp-better-popup-click-trigger');
            this._createStyleTag();
            this._removeDuplicatePopup();

            //Check show trigger
            if (this.options.dataPopup.afterSeconds.isAfterSeconds) {
                setTimeout(function () {
                    triggerElem.show();
                }, this.options.dataPopup.afterSeconds.delay * 1000);
            } else {
                triggerElem.show();
            }

            if (popupElem <= 1) {
                if (this.options.dataPopup.isScroll) {  // show when scroll
                    self._scrollToShow();
                } else if (this.options.dataPopup.isExitIntent) { //show when Exit Intent
                    $(document).mouseleave(function () {
                        bioEp.init(self.options.dataPopup.popupConfig);
                        self._fullScreen();
                        $(document).off('mouseleave');

                    });
                } else {
                    bioEp.init(self.options.dataPopup.popupConfig);

                    if (this.options.dataPopup.fullScreen.isFullScreen) {
                        if ($('#mp-popup-template3').length) {
                            $('#bio_ep').css({"width": "800px", "height": "321px"});
                        } else if ($('#mp-popup-template4').length) {
                            $('#bio_ep').css({"width": "605px", "height": "330px"});
                        } else if ($('#mp-popup-template5').length) {
                            $('#bio_ep').css({"width": "359px", "height": "260px"});
                        } else if ($('#mp-popup-template6').length) {
                            $('#bio_ep').css({"width": "800px", "height": "250px"});
                        }
                    }
                    self._fullScreen();
                }

            }
        },

        /**
         * Event click float button to show popup
         * @private
         */
        _clickTrigger: function () {
            var self = this;

            $('.mp-better-popup-click-trigger').click(function () {
                var bgEl = $('#bio_ep_bg');
                if (!bgEl.length) {
                    $('body').append("<div id='bio_ep_bg'></div>");
                    self._fullScreen();
                }

                bioEp.init(self.options.dataPopup.popupConfig);
                self._scrollToShow();
                $('#bio_ep').show();
                $('#bio_ep_bg').show();
                $('#bio_ep_close').show();
                $('#mp-newsletter-error').hide();
                $('#mp-newsletter').css('border-color', '#c2c2c2');
                $('[id]').each(function () {
                    $('[id="bio_ep_bg"]:gt(0)').remove();
                });
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
                template4 = $('#mp-popup-template4').length, // check on template 4
                template5 = $('#mp-popup-template5').length, // check on template 5
                form = $('#mp-newsletter-validate-detail');


            form.submit(function (e) {
                if (form.validation('isValid')) {
                    var email = $("#mp-newsletter").val();
                    var url = form.attr('action');

                    $('.popup-loader').show();
                    if (template4) {
                        $('.popup-loader').css({'left': '200px'});
                    }
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
                                    if (template4 || template5) {
                                        $('#bio_ep_content').css('color', '#3d3d3e');
                                        $('#mp-coupon-code').css('color', '#3d3d3e');
                                    }
                                    if (template5) {
                                        $('#bio_ep_close').css({'top': '0px'});
                                    }
                                }
                            });
                        }
                    });
                }

                //css for error message
                if (!self._checkUpdateTemplate()) {
                    $('#mp-newsletter-error').css({"position": "absolute", "width": "100%"});
                }

                if (template4) {
                    $('#mp-newsletter-error').css({"position": "absolute", "bottom": "25px", "left": "35px"});
                }

                if (template5) {
                    $('.tmp5-msg-error').html('');
                    $("#mp-newsletter-error").appendTo(".tmp5-msg-error");
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
                    self._fullScreen();
                    $(window).off('scroll');
                }
            });
        },

        /**
         * Css for full creen option
         * @private
         */
        _fullScreen: function () {
            if (this.options.dataPopup.fullScreen.isFullScreen) {
                $('#bio_ep_bg').css({'background-color': this.options.dataPopup.fullScreen.bgColor, 'opacity': 1});
            }
        },

        /**
         * Remove duplicate popup
         * @private
         */
        _removeDuplicatePopup: function () {
            $('[id]').each(function () {
                $('[id="mageplaza-betterpopup-block"]:gt(0)').remove();
            });
        },

        /**
         * Create Style tag on head
         * @private
         */
        _createStyleTag: function () {
            var head = document.head,
                style = document.createElement('style');

            style.type = 'text/css';
            head.appendChild(style);
        },

        _checkUpdateTemplate: function () {
            if ($('#mp-popup-template3').length || $('#mp-popup-template4').length || $('#mp-popup-template5').length || $('#mp-popup-template6').length) {
                return true;
            }

            return false;
        }
    });

    return $.mageplaza.betterpopup_block;
});