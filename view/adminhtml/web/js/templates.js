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

            if (textContent.indexOf('mp-popup-template4') !== -1) {
                select.val(1);
            } else if (textContent.indexOf('mp-popup-template5') !== -1) {
                select.val(2);
            } else if (textContent.indexOf('mp-popup-template6') !== -1) {
                select.val(3);
            } else {
                select.val(0);
            }
        }
    });

    return $.mageplaza.popupTemplates;
});