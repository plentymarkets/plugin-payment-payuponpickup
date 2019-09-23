<?php
namespace PayUponPickup\Assistants;

use PayUponPickup\Assistants\DataSources\AssistantDataSource;
use PayUponPickup\Assistants\SettingsHandlers\PayUponPickupAssistantSettingsHandler;
use PayUponPickup\Services\SettingsService;
use Plenty\Modules\Order\Shipping\Countries\Contracts\CountryRepositoryContract;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Wizard\Services\WizardProvider;
use Plenty\Plugin\Application;

class PayUponPickupAssistant extends WizardProvider
{
    /** @var SettingsService */
    protected $settings;
    /**
     * @var string
     */
    private $language;

    /**
     * @var WebstoreRepositoryContract
     */
    private $webstoreRepository;

    /**
     * @var array
     */
    private $deliveryCountries;

    /**
     * @var Webstore
     */
    private $mainWebstore;
    /**
     * @var Array
     */
    private $webstoreValues;

    public function __construct(
        WebstoreRepositoryContract $webstoreRepository,
        SettingsService $settings
    ) {
        $this->webstoreRepository = $webstoreRepository;
        $this->settings = $settings;
    }

    protected function structure()
    {
        return [
            "title" => 'assistant.assistantTitle',
            "shortDescription" => 'assistant.assistantShortDescription',
            "iconPath" => $this->getIcon(),
            "settingsHandlerClass" => PayUponPickupAssistantSettingsHandler::class,
            'dataSource' => AssistantDataSource::class,
            "translationNamespace" => "PayUponPickup",
            "key" => "payment-payUponPickupAssistant-assistant",
            "topics" => ["payment"],
            "priority" => 990,
            "options" => [
                "config_name" => [
                    "type" => 'select',
                    'defaultValue' => $this->getMainWebstore(),
                    "options" => [
                        "name" => 'assistant.storeName',
                        'required' => true,
                        'listBoxValues' => $this->getWebstoreListForm(),
                    ],
                ],
            ],
            "steps" => [
                "stepOne" => [
                    "title" => "assistant.stepOneTitle",
                    "sections" => [
                        [
                            "title" => 'assistant.shippingCountriesTitle',
                            "description" => 'assistant.shippingCountriesDescription',
                            "form" => [
                                "shippingCountries" => [
                                    'type' => 'checkboxGroup',
                                    'defaultValue' => [],
                                    'options' => [
                                        'name' => 'assistant.shippingCountries',
                                        'checkboxValues' => $this->getCountriesListForm(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                "stepTwo" => [
                    "title" => "assistant.stepTwoTitle",
                    "sections" => [
                        [
                            "title" => 'assistant.infoPageTitle',
                            "form" => [
                                "info_page_toggle" => [
                                    'type' => 'toggle',
                                    'options' => [
                                        'name' => 'assistant.infoPageToggle',
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'assistant.infoPageTypeTitle',
                            "description" => 'assistant.infoPageTypeDescription',
                            "condition" => 'info_page_toggle',
                            "form" => [
                                "infoPageType" => [
                                    'type' => 'select',
                                    'defaultValue' => 1,
                                    'options' => [
                                        'name' => 'assistant.infoPageTypeName',
                                        'listBoxValues' => [
                                            [
                                                "caption" => 'assistant.infoPageInternal',
                                                "value" => 1,
                                            ],
                                            [
                                                "caption" => 'assistant.infoPageExternal',
                                                "value" => 2,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ], 
                        [
                            "title" => '',
                            "description" => 'assistant.infoPageNameInternal',
                            "condition" => 'info_page_toggle && infoPageType == 1',
                            "form" => [
                                "infoPageIntern" => [
                                    "type" => 'category',
                                    'defaultValue' => '',
                                    'isVisible' => "info_page_toggle && infoPageType == 1",
                                    "displaySearch" => true,
                                    "options" => [
                                        "name" => "assistant.infoPageNameInternal"
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => '',
                            "condition" => 'info_page_toggle && infoPageType == 2',
                            "form" => [
                                "infoPageExtern" => [
                                    'type' => 'text',
                                    'defaultValue' => '',
                                    'options' => [
                                        'pattern'=> "(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})",
                                        'name' => 'assistant.infoPageNameExternal',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                "stepThree" => [
                    "title" => 'assistant.stepThreeTitle',
                    "sections" => [
                        [
                            "title" => 'assistant.sectionLogoTitle',
                            "description" => 'assistant.sectionLogoDescription',
                            "form" => [
                                "logo" => [
                                    'type' => 'toggle',
                                    'defaultValue' => false,
                                    'options' => [
                                        'name' => 'assistant.logoTypeToggle',
                                    ],
                                ],
                            ],
                        ],
                        [
                            "title" => '',
                            "description" => 'assistant.logoURLDescription',
                            "condition" => 'logo',
                            "form" => [
                                "logo_url" => [
                                    'type' => 'file',
                                    'defaultValue' => '',
                                    'showPreview' => true,
                                    'options' => [
                                        'name' => 'assistant.logoURLTypeName'
                                    ]
                                ],
                            ],
                        ],
                        [
                            "title" => 'assistant.sectionPaymentMethodIconTitle',
                            "description" => 'assistant.sectionPaymentMethodIconDescription',
                            "form" => [
                                "paymentMethodIcon" => [
                                    'type' => 'checkbox',
                                    'defaultValue' => 'false',
                                    'options' => [
                                        'name' => 'assistant.assistantPaymentMethodIconCheckbox'
                                    ]
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];
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
     * @return string
     */
    private function getIcon()
    {
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('PayUponPickup').'/images/icon.png';

        return $icon;
    }

    private function getMainWebstore(){
        if($this->mainWebstore === null) {
            /** @var WebstoreRepositoryContract $webstoreRepository */
            $webstoreRepository = pluginApp(WebstoreRepositoryContract::class);

            $this->mainWebstore = $webstoreRepository->findById(0)->storeIdentifier;
        }
        return $this->mainWebstore;
    }

    /**
     * @return array
     */
    private function getWebstoreListForm()
    {
        if($this->webstoreValues === null)
        {
            $webstores = $this->webstoreRepository->loadAll();
            /** @var Webstore $webstore */
            foreach ($webstores as $webstore) {
                $this->webstoreValues[] = [
                    "caption" => $webstore->name,
                    "value" => $webstore->storeIdentifier,
                ];
            }

            usort($this->webstoreValues, function ($a, $b) {
                return ($a['value'] <=> $b['value']);
            });
        }

        return $this->webstoreValues;
    }

    /**
     * @return array
     */
    private function getCountriesListForm()
    {
        if ($this->deliveryCountries === null) {
            /** @var CountryRepositoryContract $countryRepository */
            $countryRepository = pluginApp(CountryRepositoryContract::class);
            $countries = $countryRepository->getCountriesList(true, ['names']);
            $this->deliveryCountries = [];
            $systemLanguage = $this->getLanguage();
            foreach($countries as $country) {
                $name = $country->names->where('lang', $systemLanguage)->first()->name;
                $this->deliveryCountries[] = [
                    'caption' => $name ?? $country->name,
                    'value' => $country->id
                ];
            }
            // Sort values alphabetically
            usort($this->deliveryCountries, function($a, $b) {
                return ($a['caption'] <=> $b['caption']);
            });
        }
        return $this->deliveryCountries;
    }
}
?>
