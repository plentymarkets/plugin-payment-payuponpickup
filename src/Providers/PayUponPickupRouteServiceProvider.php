<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 23/2/17
 * Time: 15:48
 */

namespace PayUponPickup\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;
use Plenty\Plugin\Routing\ApiRouter;

class PayUponPickupRouteServiceProvider extends RouteServiceProvider
{

    /**
     * @param Router $router
     */
    public function map(Router $router , ApiRouter $apiRouter)
    {
       $apiRouter->version(['v1'], ['middleware' => ['oauth']],
            function ($routerApi)
            {
                /** @var ApiRouter $routerApi */
                $routerApi->get('payment/payuponpickup/settings/{plentyId}/{lang}'  , ['uses' => 'PayUponPickup\Controllers\SettingsController@loadSettings']);
                $routerApi->put('payment/payuponpickup/settings'                    , ['uses' => 'PayUponPickup\Controllers\SettingsController@saveSettings']);
            });
        // $router->get('payment/payuponpickup/settings/{plentyId}/{lang}',   'PrePayment\Controllers\SettingsController@loadSettings');
        // $router->put('payment/payuponpickup/settings',                     'PrePayment\Controllers\SettingsController@saveSettings');
    }

}