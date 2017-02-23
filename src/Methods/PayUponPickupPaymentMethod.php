<?php

namespace PayUponPickup\Methods;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Plugin\ConfigRepository;

/**
 * Class PayUponPickupPaymentMethod
 * @package PayUponPickup\Methods
 */
class PayUponPickupPaymentMethod extends PaymentMethodService
{

      /**
       * @var BasketRepositoryContract
       */
      private $basketRepo;


      /**
       * @var ConfigRepository
       */
      private $configRepo;

      /**
       * PayUponPickupPaymentMethod constructor.
       * @param BasketRepositoryContract $basketRepo
       * @param ConfigRepository $configRepo
       */
      public function __construct(BasketRepositoryContract    $basketRepo,
                                  ConfigRepository            $configRepo)
      {
            $this->basketRepo     = $basketRepo;
            $this->configRepo     = $configRepo;
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
            $name = $this->configRepo->get('PayUponPickup.name');

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
                  return (float)$this->configRepo->get('PayUponPickup.fee.domestic');
            }
            else
            {
                  return (float)$this->configRepo->get('PayUponPickup.fee.foreign');
            }

      }


      /**
       * Get PayUponPickup Icon
       *
       * @return string
       */
      public function getIcon( ConfigRepository $config )
      {
            if($config->get('PayUponPickup.logo') == 1)
            {
                  return $this->configRepo->get('PayUponPickup.logo.url');
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
            switch($this->configRepo->get('PayUponPickup.infoPage.type'))
            {
                  case 1:
                        return $this->configRepo->get('PayUponPickup.infoPage.extern');
                        break;

                  case 2:
                        return $this->configRepo->get('PayUponPickup.infoPage.intern');
                        break;

                  default:
                        return '';
                        break;
            }
      }


}
