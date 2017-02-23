<?php

namespace PayUponPickup\Migrations;

use PayUponPickup\Helper\PayUponPickupHelper;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;

/**
 * Migration to create payment method
 *
 * Class CreatePaymentMethod
 * @package PayUponPickup\Migrations
 */
class CreatePaymentMethod
{
    /**
     * @var PaymentMethodRepositoryContract
     */
    private $paymentMethodRepositoryContract;

    /**
     * @var PayUponPickupHelper
     */
    private $payUponPickupHelper;

    /**
     * CreatePaymentMethod constructor.
     * @param PaymentMethodRepositoryContract $paymentMethodRepositoryContract
     * @param PayUponPickupHelper $payUponPickupHelper
     */
    public function __construct(PaymentMethodRepositoryContract $paymentMethodRepositoryContract, PayUponPickupHelper $payUponPickupHelper)
    {
        $this->paymentMethodRepositoryContract = $paymentMethodRepositoryContract;
        $this->payUponPickupHelper = $payUponPickupHelper;
    }

    /**
     * Run on plugin build
     *
     * Create Method of Payment ID for PayUponPickup if it doesn't exist
     */
    public function run()
    {
        /**
         * Check if the payment method exist
         */
        if($this->payUponPickupHelper->getPayUponPickupMopId() == 'no_paymentmethod_found')
        {
            $paymentMethodData = array( 'pluginKey'     => 'plenty_payuponpickup',
                                        'paymentKey'    => 'PAYUPONPICKUP',
                                        'name'          => 'Pay upon pickup');

            //Call Payment Method Repository and Save data to DB
            $this->paymentMethodRepositoryContract->createPaymentMethod($paymentMethodData);
        }
    }
}