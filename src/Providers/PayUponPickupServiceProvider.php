<?php

namespace PayUponPickup\Providers;

use PayUponPickup\Helper\PayUponPickupHelper;
use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use PayUponPickup\Methods\PayUponPickupPaymentMethod;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;

/**
 * Class PayUponPickupServiceProvider
 * @package PayUponPickup\Providers
 */
class PayUponPickupServiceProvider extends ServiceProvider
{

    /**
     * Register the route service provider
     */
    public function register()
    {
        $this->getApplication()->register(PayUponPickupRouteServiceProvider::class);
    }

    /**
     * @param PaymentMethodContainer    $payContainer
     */
    public function boot(   PayUponPickupHelper $paymentHelper,
                            PaymentMethodContainer $payContainer)
    {

        // Create the ID of the payment method if it doesn't exist yet
        $paymentHelper->createMopIfNotExists();

        //Register the PayUponPickup Plugin
        $payContainer->register('plenty_payuponpickup::PAYUPONPICKUP', PayUponPickupPaymentMethod::class,
                                [AfterBasketChanged::class, AfterBasketCreate::class]   );
    }
}