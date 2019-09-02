<?php

namespace PayUponPickup\Assistants\DataSources;

use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Wizard\Models\WizardData;
use Plenty\Modules\Wizard\Services\DataSources\BaseWizardDataSource;

class AssistantDataSource extends BaseWizardDataSource
{
    /**
     * @var SettingsService
     */
    protected $settingsService;

    public function __construct(
        SettingsService $settingsService
    )
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @return WizardData WizardData
     */
    public function findData()
    {
        //for WizardContainer ($wizardArray['isCompleted'] = $dataSource->findData()->data->default ? true : false;)
        /** @var WizardData $wizardData */
        $wizardData = pluginApp(WizardData::class);
        $wizardData->data = ['default' => false];

        return $wizardData;
    }

    /**
     * @return array
     */
    private function getEntities()
    {
        $data = [];
        $pids = $this->settingsService->getClientsIds();
        foreach ($pids as $pid) {
            $settingsExist = $this->settingsService->clientSettingsExist($pid, null);
            if ($settingsExist) {
                $settings = $this->settingsService->getSettingsForPlentyId($pid, null);
                $data = [];
                $data[$pid] = $settings;
                $data[$pid]['config_name'] = $pid;
                if($data[$pid]['infoPageType'] > 0)
                {
                    $data[$pid]['info_page_toggle'] = true;
                }
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getIdentifiers()
    {
        return array_keys($this->getEntities());
    }

    /**
     * @return array
     */
    public function get()
    {
        $wizardData = $this->dataStructure;

        //Must be passed otherwise the tiles have no data.
        $tileConfig = [];

        $pids = $this->settingsService->getClientsIds();
        foreach ($pids as $pid) {
            $tileConfig[$pid] =
                [
                    'config_name' => $pid
                ];
        }
        $wizardData['data'] = $tileConfig;

        return $wizardData;
    }

    /**
     * @param string $optionId
     * @return array
     */
    public function getByOptionId(string $optionId = 'default')
    {
        $dataStructure = $this->dataStructure;
        $entities = $this->getEntities();

        // If this option already exists
        if($optionId > 0){
            $dataStructure['data'] = $entities[$optionId];
            $dataStructure['data']['config_name'] = $optionId;
        }

        return $dataStructure;

    }

    /**
     * @param array $data
     * @param string $optionId
     *
     * @return array
     * @throws \Exception
     */
    public function createDataOption(array $data = [], string $optionId = 'default')
    {
        throw new \Exception('incorrect setting data');
    }

    /**
     * @param string $optionId
     *
     * @throws \Exception
     */
    public function deleteDataOption(string $optionId)
    {
        $this->settingsService->deleteSettings($optionId);
    }

    /**
     * @param string $optionId
     * @param array $data
     *
     * @throws \Exception
     */
    public function finalize(string $optionId, array $data = [])
    {
        //later :)
    }

    private function loadData()
    {
        $data = [];
        return $data;
    }
}
