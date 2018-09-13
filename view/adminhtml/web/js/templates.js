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
    "jquery",
    'Magento_Ui/js/modal/confirm',
    "mage/translate",
    "jquery/ui"
], function ($, confirm, $t) {
    "use strict";

    $.widget('mageplaza.popupTemplates', {
        options: {
            jsonDataInfo: {}
        },
        ids: {
            popupFullScreen: '#betterpopup_what_to_show_responsive',
            popupHtmlElm: '#betterpopup_what_to_show_html_content',
            successHtmlElm: '#betterpopup_what_to_show_popup_success_html_success_content',
            popupBackground: '#betterpopup_what_to_show_background_color',
            popupTextColor: '#betterpopup_what_to_show_text_color',
            popupWidth: '#betterpopup_what_to_show_width',
            popupHeight: '#betterpopup_what_to_show_height'
        },

        _create: function () {
            var self = this,
                elem = self.element.next();

            self._setSelect();
            elem.click(function (e) {
                confirm({
                    content: $t('Are you sure to load HTML?'),
                    actions: {
                        /** @inheritdoc */
                        confirm: function () {
                            e.preventDefault();
                            self._autoFill();
                        }
                    }
                });
            });
        },

        /**
         * load config when select template
         * @private
         */
        _autoFill: function () {
            var dataInfo = this.options.jsonDataInfo,
                value = parseInt(this.element.val());

            if (value >= 0) {
                var data = dataInfo[value];
                if (data) {
                    $(this.ids.popupHtmlElm).val(data.popupHtml);
                    $(this.ids.successHtmlElm).val(data.successHtml);
                    $(this.ids.popupBackground).val(data.background);
                    $(this.ids.popupTextColor).val(data.textColor);
                    $(this.ids.popupWidth).val(data.width);
                    $(this.ids.popupHeight).val(data.height);
                    if (value == 3 || value == 4 || value == 5 || value == 6) {
                        this._setReadOnly();
                    } else {
                        this._removeReadOnly();
                    }
                }
            }
        },

        /**
         * Set select when load page
         * @private
         */
        _setSelect: function () {
            var select = $('#mageplaza_betterpopup_templates'),
                textContent = $('#betterpopup_what_to_show_html_content').text();

            if (textContent.indexOf('mp-popup-template1') !== -1) {
                select.val(1);
            } else if (textContent.indexOf('mp-popup-template2') !== -1) {
                select.val(2);
            } else if (textContent.indexOf('mp-popup-template3') !== -1) {
                select.val(3);
                this._setReadOnly();
            } else if (textContent.indexOf('mp-popup-template4')!== -1) {
                select.val(4);
                this._setReadOnly();
            } else if (textContent.indexOf('mp-popup-template5')!== -1) {
                select.val(5);
                this._setReadOnly();
            } else if (textContent.indexOf('mp-popup-template6')!== -1) {
                select.val(6);
                this._setReadOnly();
            } else {
                select.val(0);
            }
        },

        _setReadOnly: function () {
            $(this.ids.popupFullScreen).val(1);
            $(this.ids.popupFullScreen).attr("disabled", true);
            $(this.ids.popupWidth).prop('readonly', true);
            $(this.ids.popupHeight).prop('readonly', true);
            $(this.ids.popupBackground).prop('readonly', true);
        },

        _removeReadOnly: function () {
            $(this.ids.popupFullScreen).removeAttr("disabled");
            $(this.ids.popupWidth).prop('readonly', false);
            $(this.ids.popupHeight).prop('readonly', false);
            $(this.ids.popupBackground).prop('readonly', false);
        }
    });

    return $.mageplaza.popupTemplates;
});