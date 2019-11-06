<?php

namespace PayUponPickup\Providers;

use PayUponPickup\Extensions\PayUponPickupTwigServiceProvider;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use PayUponPickup\Methods\PayUponPickupPaymentMethod;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Plugin\Templates\Twig;
use Plenty\Modules\Wizard\Contracts\WizardContainerContract;
use PayUponPickup\Assistants\PayUponPickupAssistant;

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
     * @param PaymentMethodContainer $payContainer
     */
    public function boot(   Twig $twig,
                            PaymentMethodContainer $payContainer)
    {
        pluginApp(WizardContainerContract::class)->register('payment-payUponPickupAssistant-assistant', PayUponPickupAssistant::class);
        $twig->addExtension(PayUponPickupTwigServiceProvider::class);

        //Register the PayUponPickup Plugin
        $payContainer->register('plenty::CASH', PayUponPickupPaymentMethod::class,
            [ AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class]   );
    }
}
