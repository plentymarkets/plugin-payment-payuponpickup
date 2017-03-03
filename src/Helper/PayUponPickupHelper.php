<?php //strict

namespace PayUponPickup\Helper;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodRepositoryContract;
use Plenty\Modules\Payment\Method\Models\PaymentMethod;

class PayUponPickupHelper
{

    /**
    * @var PaymentMethodRepositoryContract
    */
    private $paymentMethodRepository;

    /**
    * PrePaymentHelper constructor.
    *
    * @param PaymentMethodRepositoryContract $paymentMethodRepository
    */
    public function __construct(PaymentMethodRepositoryContract $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function createMopIfNotExists()
    {
        // Check whether the ID of the Invoice payment method has been created
        if($this->getPayUponPickupMopId() == 'no_paymentmethod_found')
        {
            $paymentMethodData = array( 'pluginKey' => 'plenty_payuponpickup',
                                        'paymentKey' => 'PAYUPONPICKUP',
                                        'name' => 'Bar Zahlung bei Abholung');

            $this->paymentMethodRepository->createPaymentMethod($paymentMethodData);
        }
    }

    /**
    * Get PayUponPickup Method of Payment ID
    *
    * @return mixed
    */
    public function getPayUponPickupMopId()
    {
        //Load plenty_payuponpickup plugin data from DB
        $paymentMethods = $this->paymentMethodRepository->allForPlugin('plenty_payuponpickup');

        if( !is_null($paymentMethods) )
        {
              foreach($paymentMethods as $paymentMethod)
              {
                    if($paymentMethod->paymentKey == 'PAYUPONPICKUP')
                    {
                          return $paymentMethod->id;
                    }
              }
        }

        return 'no_paymentmethod_found';
    }

}
