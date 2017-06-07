<?php

namespace PayUponPickup\Methods;

use IO\Services\SessionStorageService;
use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\ConfigRepository;

/**
 * Class PayUponPickupPaymentMethod
 * @package PayUponPickup\Methods
 */
class PayUponPickupPaymentMethod extends PaymentMethodService
{
    /** @var BasketRepositoryContract */
    private $basketRepo;

    /** @var  SettingsService */
    private $settings;

    /** @var  Checkout */
    private $checkout;

    /**
    * PayUponPickupPaymentMethod constructor.
    * @param BasketRepositoryContract $basketRepo
    * @param SettingsService          $settingsService
    * @param Checkout                 $checkout
    */
    public function __construct(BasketRepositoryContract    $basketRepo,
                                SettingsService             $settingsService,
                                Checkout $checkout)
    {
        $this->basketRepo     = $basketRepo;
        $this->settings     = $settingsService;
        $this->checkout       = $checkout;
    }

    /**
    * Check whether PayUponPickup is active or not
    *
    * @return bool
    */
    public function isActive()
    {
        if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getSetting('shippingCountries')))
        {
            return false;
        }

        return true;
    }

    /**
    * Get shown name
    *
    * @return string
    */
    public function getName()
    {
        /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;

        if(!empty($lang))
        {
            $name = $this->settings->getSetting('name', $lang);
        }
        else
        {
            $name = $this->settings->getSetting('name');
        }

        return $name;
    }

    /**
    * Get PayUponPickup Fee
    *
    * @return float
    */
    public function getFee()
    {
        $basket = $this->basketRepo->load();

        // Shipping Country ID with ID = 1 belongs to Germany
        if($basket->shippingCountryId == 1)
        {
              return (float)$this->settings->getSetting('feeDomestic');
        }
        else
        {
              return (float)$this->settings->getSetting('feeForeign');
        }
    }

    /**
    * Get PayUponPickup Icon
    *
    * @return string
    */
    public function getIcon( ConfigRepository $config )
    {
        if( $this->settings->getSetting('logo') == 1)
        {
            return $this->settings->getSetting('logoUrl');
        }
        elseif($this->settings->getSetting('logo') == 2)
        {
            return 'layout/plugins/production/prepayment/images/icon.png';
        }

        return '';
    }

    /**
    * Get PayUponPickup Description
    *
    * @param ConfigRepository $config
    * @return string
    */
    public function getDescription( ConfigRepository $config )
    {
        switch($this->settings->getSetting('infoPageType'))
        {
              case 1:
                    return $this->settings->getSetting('infoPageExtern');
                    break;

              case 2:
                    return $this->settings->getSetting('infoPageIntern');
                    break;

              default:
                    return '';
                    break;
        }
    }
    
    /**
     * Check if it is allowed to switch to this payment method
     *
     * @return bool
     */
    public function switchTo()
    {
        return true;
    }
    
    /**
     * Check if it is allowed to switch from this payment method
     *
     * @return bool
     */
    public function switchFrom()
    {
        return true;
    }
}