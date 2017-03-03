<?php

namespace PayUponPickup\Methods;

use IO\Services\SessionStorageService;
use PayUponPickup\Services\SettingsService;
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


    /** @var ConfigRepository */
    private $configRepo;

    /** @var  SettingsService */
    private $settings;

    /**
    * PayUponPickupPaymentMethod constructor.
    * @param BasketRepositoryContract $basketRepo
    * @param ConfigRepository $configRepo
    */
    public function __construct(BasketRepositoryContract    $basketRepo,
                                ConfigRepository            $configRepo,
                                SettingsService             $settingsService)
    {
        $this->basketRepo     = $basketRepo;
        $this->configRepo     = $configRepo;
        $this->settings     = $settingsService;
    }

    /**
    * Check whether PayUponPickup is active or not
    *
    * @return bool
    */
    public function isActive()
    {
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
        if($this->settings->getSetting('logo') == 1)
        {
              return $this->settings->getSetting('logoUrl');
        }

        return 'layout/plugins/production/payuponpickup/images/icon.png';
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
                    return $this->configRepo->get('infoPageExtern');
                    break;

              case 2:
                    return $this->configRepo->get('infoPageIntern');
                    break;

              default:
                    return '';
                    break;
        }
    }


}
