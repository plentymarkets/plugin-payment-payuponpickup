<?php

namespace PayUponPickup\Providers;

use PayUponPickup\Extensions\PayUponPickupTwigServiceProvider;
use PayUponPickup\Helper\PayUponPickupHelper;
use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use PayUponPickup\Methods\PayUponPickupPaymentMethod;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Plugin\Templates\Twig;

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
     * Boot additional services for the payment method
     *
     * @param Twig $twig
     * @param PayUponPickupHelper $paymentHelper
     * @param PaymentMethodContainer $payContainer
     */
    public function boot(   Twig $twig,
                            PayUponPickupHelper $paymentHelper,
                            PaymentMethodContainer $payContainer)
    {
        $twig->addExtension(PayUponPickupTwigServiceProvider::class);

        // Create the ID of the payment method if it doesn't exist yet
        $paymentHelper->createMopIfNotExists();

        //Register the PayUponPickup Plugin
        $payContainer->register('plenty_payuponpickup::PAYUPONPICKUP', PayUponPickupPaymentMethod::class,
                                [AfterBasketChanged::class, AfterBasketCreate::class]   );
    }
}