<?php

class Vindi_Subscription_Model_BankSlip extends Mage_Payment_Model_Method_Abstract
{
    use Vindi_Subscription_Trait_PaymentMethod;

    /**
     * @var string
     */
    protected $_code = 'vindi_bankslip';

    /**
     * @var bool
     */
    protected $_isGateway = true;

    /**
     * @var bool
     */
    protected $_canAuthorize = true;

    /**
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * @var bool
     */
    protected $_canCapturePartial = false;

    /**
     * @var bool
     */
    protected $_canRefund = false;

    /**
     * @var bool
     */
    protected $_canVoid = false;

    /**
     * @var bool
     */
    protected $_canUseInternal = true;

    /**
     * @var bool
     */
    protected $_canUseCheckout = true;

    /**
     * @var bool
     */
    protected $_canUseForMultishipping = false;

    /**
     * @var bool
     */
    protected $_isInitializeNeeded = true;

    /**
     * @var bool
     */
    protected $_canSaveCc = false;

    /**
     * @var string
     */
    protected $_formBlockType = 'vindi_subscription/form_bankSlip';

    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     *
     * @return  Mage_Payment_Model_Method_Abstract
     */
    public function assignData($data)
    {
        $info = $this->getInfoInstance();
        $info->setAdditionalInformation('installments', 1);
        return $this;
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     *
     * @return bool|Mage_Payment_Model_Method_Abstract
     */
    protected function processNewOrder($paymentAction, $stateObject)
    {
        $payment = $this->getInfoInstance();
        $order = $payment->getOrder();

        $customer = Mage::getModel('customer/customer');

        $customerId = $this->createCustomer($order, $customer);

        if ($this->isSingleOrder($order)) {
            $bill = $this->processSinglePayment($payment, $order, $customerId);
        } else {
            $bill = $this->processSubscription($payment, $order, $customerId);
        }

        if (! $bill) {
            return false;
        }

        $stateObject->setStatus(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT)
            ->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);

        return $this;
    }

    /**
     * Check whether payment method can be used
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        /** @var Vindi_Subscription_Helper_API $api */
        $api = Mage::helper('vindi_subscription/api');

        return Mage::getStoreConfig('payment/vindi_bankslip/active')
        && $api->acceptBankSlip();
    }

    /**
     * @return string
     */
    protected function getPaymentMethodCode()
    {
        // TODO fix it to proper method code
        return 'bank_slip';
    }
}
