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
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use Magento\Newsletter\Model\Subscriber;
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
     * @var SubscriptionManagerInterface
     */
    protected $subscriberManagerInterface;

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
        $this->resultJsonFactory          = $jsonFactory;
        $this->_helperData                = $helperData;
        $this->subscriberManagerInterface = $subscriptionManager;

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

                $websiteId  = (int)$this->_storeManager->getStore()->getWebsiteId();
                $subscriber = $this->_subscriberFactory->create()->loadBySubscriberEmail($email, $websiteId);
                if ($subscriber->getId()
                    && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED) {
                    $response = [
                        'success' => false,
                        'msg'     => __('This email address is already subscribed.')
                    ];

                    return $this->resultJsonFactory->create()->setData($response);
                }
                $storeId           = (int)$this->_storeManager->getStore()->getId();
                $currentCustomerId = $this->getSessionCustomerId($email);
                $subscriber        = $currentCustomerId
                    ? $this->subscriberManagerInterface->subscribeCustomer($currentCustomerId, $storeId)
                    : $this->subscriberManagerInterface->subscribe($email, $storeId);
                $message           = $this->getSuccessMessage((int)$subscriber->getSubscriberStatus());
                $response          = [
                    'success' => true,
                    'msg'     => $message,
                ];
            } catch (LocalizedException $e) {
                $response = [
                    'success' => false,
                    'msg'     => __('There was a problem with the subscription: %1', $e->getMessage()),
                ];
            } catch (Exception $e) {
                $response = [
                    'status' => false,
                    'msg'    => __('Something went wrong with the subscription: %1', $e->getMessage()),
                ];
            }
        }

        return $this->resultJsonFactory->create()->setData($response);
    }

    /**
     * Get customer id from session if he is owner of the email
     *
     * @param string $email
     *
     * @return int|null
     */
    private function getSessionCustomerId(string $email): ?int
    {
        if (!$this->_customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->_customerSession->getCustomerDataObject();
        if ($customer->getEmail() !== $email) {
            return null;
        }

        return (int)$this->_customerSession->getId();
    }

    /**
     * Get success message
     *
     * @param int $status
     *
     * @return Phrase
     */
    private function getSuccessMessage(int $status): Phrase
    {
        if ($status === Subscriber::STATUS_NOT_ACTIVE) {
            return __('The confirmation request has been sent.');
        }

        return __('Thank you for your subscription.');
    }
}
