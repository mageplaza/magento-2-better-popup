<?php

namespace Mageplaza\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\RuleFactory;

/**
 * Class CartRules
 * @package Mageplaza\BetterPopup\Model\Config\Source
 */
class CartRules implements ArrayInterface
{
    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * CartRules constructor.
     *
     * @param RuleFactory $ruleFactory
     */
    public function __construct(RuleFactory $ruleFactory)
    {
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $option = [['value' => 0, 'label' => __('-- Please Select --')]];
        $ruleCollection = $this->ruleFactory->create()->getCollection();
        foreach ($ruleCollection as $rule) {
            if ($rule->getIsActive()
                && (int)$rule->getCouponType() === Rule::COUPON_TYPE_SPECIFIC
                && $rule->getUseAutoGeneration()
            ) {
                $option[] = [
                    'value' => $rule->getId(),
                    'label' => $rule->getName()
                ];
            }
        }

        return $option;
    }
}
