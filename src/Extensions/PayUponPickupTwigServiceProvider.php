<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 28/2/17
 * Time: 13:48
 */

namespace PayUponPickup\Extensions;

use Plenty\Plugin\Templates\Extensions\Twig_Extension;
use PayUponPickup\Services\SessionStorageService;
use PayUponPickup\Services\SettingsService;

class PayUponPickupTwigServiceProvider extends Twig_Extension
{
    /**
     * Return the name of the extension. The name must be unique.
     *
     * @return string The name of the extension
     */
    public function getName():string
    {
        return "PayUponPickup_Extension_TwigServiceProvider";
    }

    /**
     * Return a list of filters to add.
     *
     * @return array The list of filters to add.
     */
    public function getFilters():array
    {
        return [];
    }

    /**
     * Return a list of functions to add.
     *
     * @return array the list of functions to add.
     */
    public function getFunctions():array
    {
        return [];
    }

    /**
     * Return a map of global helper objects to add.
     *
     * @return array the map of helper objects to add.
     */
    public function getGlobals():array
    {
        return [
            "payuponpickup" => [
                "settings"          => pluginApp( SettingsService::class ),
                "sessionStorage"    => pluginApp( SessionStorageService::class)
            ]
        ];
    }
}