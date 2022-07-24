<?php
namespace Diligent\CouponList\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Diligent\CouponList\Model\Rule\Collection
     */
    protected $_ruleCollection;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Diligent\CouponList\Helper\Data
     */
    protected $_helperData;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        \Diligent\CouponList\Model\Rule\Collection $ruleCollection,
        \Diligent\CouponList\Helper\Data $helperData,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_ruleCollection = $ruleCollection;
        $this->_helperData = $helperData;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * Provides checkout configurations for coupon code list.
     */
    public function getConfig()
    {
        if (!$this->_helperData->isEnabled()) {
            return [];
        }

        $couponList['couponList'] = $this->getListArray();

        return $couponList;
    }

    /**
     * get List of valid coupon code for active cart.
     */
    public function getListArray()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->_checkoutSession->getQuote();

        return $this->_ruleCollection->getValidCouponList($quote);
    }
}
