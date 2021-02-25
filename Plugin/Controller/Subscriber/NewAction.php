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
use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
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
     * NewAction constructor.
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CustomerUrl $customerUrl
     * @param CustomerAccountManagement $customerAccountManagement
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param JsonFactory $jsonFactory
     * @param Data $helperData
     * @param EmailValidator|null $emailValidator
     */
    public function __construct(
        Context $context,
        SubscriberFactory $subscriberFactory,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        CustomerUrl $customerUrl,
        CustomerAccountManagement $customerAccountManagement,
        SubscriptionManagerInterface $subscriptionManager,
        JsonFactory $jsonFactory,
        Data $helperData,
        EmailValidator $emailValidator = null
    ) {
        $this->resultJsonFactory = $jsonFactory;
        $this->_helperData = $helperData;

        parent::__construct(
            $context,
            $subscriberFactory,
            $customerSession,
            $storeManager,
            $customerUrl,
            $customerAccountManagement,
            $subscriptionManager,
            $emailValidator
        );
    }

    /**
     * @param $subject
     * @param $proceed
     *
     * @return Json
     */
    public function aroundExecute($subject, $proceed)
    {
        if (!$this->_helperData->isEnabled() || !$this->getRequest()->isAjax()) {
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
                if (!$this->_helperData->versionCompare('2.2.0')) {
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

        return $this->resultJsonFactory->create()->setData($response);
    }
}
