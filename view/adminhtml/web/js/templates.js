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
            popupHtmlElm: '#betterpopup_what_to_show_html_content',
            successHtmlElm: '#betterpopup_what_to_show_popup_success_html_success_content'
        },

        _create: function () {
            var self = this,
                elem = self.element.next();

            elem.click(function (e) {
                confirm({
                    content: $t('Are you sure to load Html?'),
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

        _autoFill: function () {
            var dataInfo = this.options.jsonDataInfo,
                value = parseInt(this.element.val());

            if (value >= 0) {
                var data = dataInfo[value];
                if (data) {
                    $(this.ids.popupHtmlElm).val(data.popupHtml);
                    $(this.ids.successHtmlElm).val(data.successHtml);
                }
            }
        }
    });

    return $.mageplaza.popupTemplates;
});