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
