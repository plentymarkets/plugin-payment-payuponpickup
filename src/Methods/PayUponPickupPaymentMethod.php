<?php

namespace PayUponPickup\Methods;

use IO\Services\SessionStorageService;
use PayUponPickup\Helper\PayUponPickupHelper;
use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Modules\Payment\Method\Services\PaymentMethodBaseService;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Application;
use Plenty\Plugin\Translation\Translator;

/**
 * Class PayUponPickupPaymentMethod
 * @package PayUponPickup\Methods
 */
class PayUponPickupPaymentMethod extends PaymentMethodBaseService
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
    public function isActive(): bool
    {
        if(!in_array($this->checkout->getShippingCountryId(), $this->settings->getShippingCountries()))
        {
            return false;
        }

        return true;
    }

    /**
     * Get shown name
     *
     * @param $lang
     * @return string
     */
    public function getName(string $lang = 'de'): string
    {
        /** @var Translator $translator */
        $translator = pluginApp(Translator::class);
        return $translator->trans('PayUponPickup::PaymentMethod.paymentMethodName',[],$lang);
    }

    /**
    * Return Payment Method Fee
    *
    * @return float
    */
    public function getFee(): float
    {

        return 0.00;
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
     * @param  string  $lang
     * @return string
     * @throws \Plenty\Exceptions\ValidationException
     */
    public function getIcon(string $lang = 'de'): string
    {
        if( $this->settings->getSetting('logo') == 1)
        {
            return $this->settings->getSetting('logoUrl');
        }
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('payuponpickup').'/images/icon.png';
        return $icon;
    }

    /**
    * Get PayUponPickup Description
    *
    * @param string $lang
    * @return string
    */
    public function getDescription(string $lang = 'de'): string
    {
      /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;
        /** @var Translator $translator */
        $translator = pluginApp(Translator::class);
        return $translator->trans('PayUponPickup::PaymentMethod.paymentMethodDescription',[],$lang);
    }

    /**
     * Check if it is allowed to switch to this payment method
     *
     * @return bool
     */
    public function isSwitchableTo(): bool
    {
        return true;
    }

    /**
     * Check if it is allowed to switch from this payment method
     *
     * @return bool
     */
    public function isSwitchableFrom(): bool
    {
        return true;
    }

    /**
     * Get PrepaymentSourceUrl
     *
     * @param string $lang
     * @return string
     */
    public function getSourceUrl(string $lang = 'de'): string
    {
        /** @var FrontendSessionStorageFactoryContract $session */
        $session = pluginApp(FrontendSessionStorageFactoryContract::class);
        $lang = $session->getLocaleSettings()->language;

        $infoPageType = $this->settings->getSetting('infoPageType', $lang);

        switch ($infoPageType)
        {
            case 1:
                // internal
                $categoryId = (int) $this->settings->getSetting('infoPageIntern', $lang);
                if($categoryId  > 0)
                {
                    /** @var PayUponPickupHelper $payUponPickupHelper */
                    $payUponPickupHelper = pluginApp(PayUponPickupHelper::class);
                    /** @var CategoryRepositoryContract $categoryContract */
                    $categoryContract = pluginApp(CategoryRepositoryContract::class);
                    return $payUponPickupHelper->getDomain() . '/' . $categoryContract->getUrl($categoryId, $lang);
                }
                return '';
            case 2:
                // external
                return $this->settings->getSetting('infoPageExtern', $lang);
            default:
                return '';
        }
    }

    /**
     * Check if this payment method should be searchable in the backend
     *
     * @return bool
     */
    public function isBackendSearchable():bool
    {
        return true;
    }

    /**
     * Check if this payment method should be active in the backend
     *
     * @return bool
     */
    public function isBackendActive():bool
    {
        return true;
    }

    /**
     * Get the name for the backend
     *
     * @param string $lang
     * @return string
     */
    public function getBackendName(string $lang = 'de'): string
    {
        return $this->getName($lang);
    }

    /**
     * Check if this payment method can handle subscriptions
     *
     * @return bool
     */
    public function canHandleSubscriptions():bool
    {
        return true;
    }

    /**
     * Get the url for the backend icon
     *
     * @return string
     */
    public function getBackendIcon(): string
    {
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('payuponpickup').'/images/logos/payuponpickup_backend_icon.svg';
        return $icon;
    }
}
