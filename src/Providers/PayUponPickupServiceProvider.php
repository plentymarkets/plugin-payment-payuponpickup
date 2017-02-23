<?php

namespace PayUponPickup\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use PayUponPickup\Methods\PayUponPickupPaymentMethod;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;

/**
 * Class PrePaymentServiceProvider
 * @package PrePayment\Providers
 */
class PrePaymentServiceProvider extends ServiceProvider
{

      /**
       * Register the route service provider
       */
      public function register()
      {

      }

      /**
       * @param PaymentMethodContainer    $payContainer
       */
      public function boot(PaymentMethodContainer $payContainer)
      {
            //Register the Pre Payment Plugin
            $payContainer->register('plenty_payuponpickup::PAYUPONPICKUP', PayUponPickupPaymentMethod::class,
                                    [AfterBasketChanged::class, AfterBasketCreate::class]   );
      }
}