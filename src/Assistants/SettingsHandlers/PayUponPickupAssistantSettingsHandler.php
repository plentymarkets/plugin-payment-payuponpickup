<?php

namespace PayUponPickup\Assistants\SettingsHandlers;
use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Plugin\Contracts\PluginLayoutContainerRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Contracts\WizardSettingsHandler;

class PayUponPickupAssistantSettingsHandler implements WizardSettingsHandler
{
    /**
     * @var Webstore
     */
    private $webstore;
    /**
     * @var Plugin
     */
    private $payUponPickup;
    /**
     * @var Plugin
     */
    private $ceresPlugin;

    /**
     * @var Plugin
     */
    private $language;

    /**
     * @param array $parameter
     * @return bool
     */
    public function handle(array $parameter)
    {
        $data = $parameter['data'];
        $webstoreId = $data['config_name'];

        if(!is_numeric($webstoreId) || $webstoreId <= 0){
            $webstoreId = $this->getWebstore($parameter['optionId'])->storeIdentifier;
        }

        $this->saveSettings($webstoreId, $data);
        $this->createContainer($webstoreId, $data);
        return true;
    }

    /**
     * @param int $webstoreId
     * @param array $data
     */
    private function saveSettings($webstoreId, $data)
    {
        $settings = [
            'name' => $data['name'] ?? '',
            'infoPageType' => $data['infoPageType'] ?? 0,
            'infoPageIntern' => $data['infoPageIntern'] ?? '',
            'infoPageExtern' => $data['infoPageExtern'] ?? '',
            'logo' => $data['logo'] ? 1 : 2,
            'logoUrl' => $data['logo_url'] ?? '',
            'plentyId' => $webstoreId,
            'shippingCountries' => $data['shippingCountries'] ?? [],
            'feeDomestic' => 0.00,
            'feeForeign' => 0.00,
            'lang' => $this->getLanguage()
        ];
        /** @var SettingsService $settingsService */
        $settingsService = pluginApp(SettingsService::class);
        $getSettings = $settingsService->clientSettingsExist($webstoreId);
        if(!$getSettings){
            $settingsService->updateClient($webstoreId);
        }
        $settingsService->saveSettings($settings);
    }

    /**
     * @return string
     */
    private function getLanguage()
    {
        if ($this->language === null) {
            $this->language =  \Locale::getDefault();
        }

        return $this->language;
    }

    /**
     * @param int $webstoreId
     * @return Webstore
     */
    private function getWebstore($webstoreId)
    {
        if ($this->webstore === null) {
            /** @var WebstoreRepositoryContract $webstoreRepository */
            $webstoreRepository = pluginApp(WebstoreRepositoryContract::class);
            $this->webstore = $webstoreRepository->findByStoreIdentifier($webstoreId);
        }

        return $this->webstore;
    }

    /**
     * Check if a string is a valid UUID.
     *
     * @param string $string
     * @return false|int
     */
    public static function isValidUUIDv4($string)
    {
        $regex = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        return preg_match($regex, $string);
    }

    /**
     * @param int $webstoreId
     * @return Plugin
     */
    private function getCeresPlugin($webstoreId)
    {
        if ($this->ceresPlugin === null) {
            $webstore = $this->getWebstore($webstoreId);
            $pluginSet = $webstore->pluginSet;
            $plugins = $pluginSet->plugins();
            $this->ceresPlugin = $plugins->where('name', 'Ceres')->first();
        }

        return $this->ceresPlugin;
    }

    /**
     * @param int $webstoreId
     * @return Plugin
     */
    private function getPayUponPickupPlugin($webstoreId)
    {
        if ($this->payUponPickup === null) {
            $webstore = $this->getWebstore($webstoreId);
            $pluginSet = $webstore->pluginSet;
            $plugins = $pluginSet->plugins();
            $this->payUponPickup = $plugins->where('name', 'PayUponPickup')->first();
        }

        return $this->payUponPickup;
    }

    /**
     * @param int $webstoreId
     * @param array $data
     */
    private function createContainer($webstoreId, $data)
    {
        $webstore = $this->getWebstore($webstoreId);
        $payUponPickupPlugin = $this->getPayUponPickupPlugin($webstoreId);
        $ceresPlugin = $this->getCeresPlugin($webstoreId);

        if( ($webstore && $webstore->pluginSetId) &&  $payUponPickupPlugin !== null && $ceresPlugin !== null) {
            /** @var PluginLayoutContainerRepositoryContract $pluginLayoutContainerRepo */
            $pluginLayoutContainerRepo = pluginApp(PluginLayoutContainerRepositoryContract::class);

            $containerListEntries = [];

            if (isset($data['paymentMethodIcon']) && $data['paymentMethodIcon']) {
                $containerListEntries[] = $this->createContainerDataListEntry(
                    $webstoreId,
                    'Ceres::Homepage.PaymentMethods',
                    'PayUponPickup\Providers\Icon\IconProvider'
                );
            } else {
                $pluginLayoutContainerRepo->removeOne(
                    $webstore->pluginSetId,
                    'Ceres::Homepage.PaymentMethods',
                    'PayUponPickup\Providers\Icon\IconProvider',
                    $ceresPlugin->id,
                    $payUponPickupPlugin->id
                );
            }

            $pluginLayoutContainerRepo->addNew($containerListEntries, $webstore->pluginSetId);
        }
    }

    /**
     * @param int $webstoreId
     * @param string $containerKey
     * @param string $dataProviderKey
     * @return array
     */
    private function createContainerDataListEntry($webstoreId, $containerKey, $dataProviderKey)
    {
        $webstore = $this->getWebstore($webstoreId);
        $payUponPickupPlugin = $this->getPayUponPickupPlugin($webstoreId);
        $ceresPlugin = $this->getCeresPlugin($webstoreId);

        $dataListEntry = [];

        $dataListEntry['containerKey'] = $containerKey;
        $dataListEntry['dataProviderKey'] = $dataProviderKey;
        $dataListEntry['dataProviderPluginId'] = $payUponPickupPlugin->id;
        $dataListEntry['containerPluginId'] = $ceresPlugin->id;
        $dataListEntry['pluginSetId'] = $webstore->pluginSetId;
        $dataListEntry['dataProviderPluginSetEntryId'] = $payUponPickupPlugin->pluginSetEntries[0]->id;
        $dataListEntry['containerPluginSetEntryId'] = $ceresPlugin->pluginSetEntries[0]->id;

        return $dataListEntry;
    }
}
