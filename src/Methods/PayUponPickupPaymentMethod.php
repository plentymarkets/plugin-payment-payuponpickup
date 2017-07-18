<?php

namespace PayUponPickup\Methods;

use IO\Services\SessionStorageService;
use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
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
      /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;
        return $this->settings->getSetting('description', $lang);
    }

    /**
     * Check if it is allowed to switch to this payment method
     *
     * @return bool
     */
    public function isSwitchableTo()
    {
        return true;
    }

    /**
     * Check if it is allowed to switch from this payment method
     *
     * @return bool
     */
    public function isSwitchableFrom()
    {
        return true;
    }

    /**
     * Get PrepaymentSourceUrl
     *
     * @return string
     */
    public function getSourceUrl()
    {
        /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;

        $infoPageType = $this->settings->getSetting('infoPageType');

        switch ($infoPageType)
        {
            case 1:
                // internal
                $categoryId = (int) $this->settings->getSetting('infoPageIntern', $lang);
                if($categoryId  > 0)
                {
                    /** @var CategoryRepositoryContract $categoryContract */
                    $categoryContract = pluginApp(CategoryRepositoryContract::class);
                    return $categoryContract->getUrl($categoryId, $lang);
                }
                return '';
            case 2:
                // external
                return $this->settings->getSetting('infoPageExtern', $lang);
            default:
                return '';
        }
    }
}
