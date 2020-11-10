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
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BetterPopup\Plugin\Controller\Subscriber;

use Exception;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\SubscriberFactory;
use Mageplaza\BetterPopup\Helper\Data;

/**
 * Class NewAction
 * @package Mageplaza\BetterPopup\Plugin\Controller\Subscriber
 */
class NewAction extends \Magento\Newsletter\Controller\Subscriber\NewAction
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @param $subject
     * @param $proceed
     *
     * @return Json
     */
    public function aroundExecute($subject, $proceed)
    {
        $resultJsonFactory = ObjectManager::getInstance()->get(JsonFactory::class);
        $_helperData = ObjectManager::getInstance()->get(Data::class);

        if (!$_helperData->isEnabled() || !$this->getRequest()->isAjax()) {
            return $proceed();
        }

        $response = [];
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $email = (string)$this->getRequest()->getPost('email');

            try {
                $this->validateEmailFormat($email);
                $this->validateGuestSubscription();
                $this->validateEmailAvailable($email);

                $this->_subscriberFactory->create()->subscribe($email);
                if (!$_helperData->versionCompare('2.2.0')) {
                    $this->_subscriberFactory->create()
                        ->loadByEmail($email)
                        ->setChangeStatusAt(date('Y-m-d h:i:s'))->save();
                }
            } catch (LocalizedException $e) {
                $response = [
                    'success' => true,
                    'msg' => __('There was a problem with the subscription: %1', $e->getMessage()),
                ];
            } catch (Exception $e) {
                $response = [
                    'status' => 'ERROR',
                    'msg' => __('Something went wrong with the subscription: %1', $e->getMessage()),
                ];
            }
        }

        return $resultJsonFactory->create()->setData($response);
    }
}
