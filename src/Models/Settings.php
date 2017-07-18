<?php
/**
 * Created by IntelliJ IDEA.
 * User: ckunze
 * Date: 23/2/17
 * Time: 12:10
 */

namespace PayUponPickup\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * Class Settings
 *
 * @property int $id
 * @property int $plentyId
 * @property string $lang
 * @property string $name
 * @property string $value
 * @property string $updatedAt
 */
class Settings extends Model
{
    const AVAILABLE_SETTINGS = array(        "plentyId"            => "int"     ,
                                             "lang"                => "string"  ,
                                             "name"                => "string"  ,
                                             "infoPageType"        => "int"     ,
                                             "infoPageIntern"      => "int"     ,
                                             "infoPageExtern"      => "string"  ,
                                             "description"         => "string"  ,
                                             "shippingCountries"   => ['int']   ,
                                             "logo"                => "int"     ,
                                             "logoUrl"             => "string"  ,
                                             "feeDomestic"         => "float"   ,
                                             "feeForeign"          => "float"   );

    const SETTINGS_DEFAULT_VALUES = array(   "infoPageType"        => "0"                ,
                                             "shippingCountries"   => ""                 ,
                                             "feeDomestic"         => "0.00"             ,
                                             "feeForeign"          => "0.00"             ,
                                             "de"  => array( "name"                => "Barzahlung"       ,
                                                             "infoPageIntern"      => ""                 ,
                                                             "infoPageExtern"      => ""                 ,
                                                             "logo"                => "2"                ,
                                                             "logoUrl"             => ""                 ,
                                                             "description"         => ""                 ),
                                             "en"  => array( "name"                => "Pay upon pickup"  ,
                                                             "infoPageIntern"      => ""                 ,
                                                             "infoPageExtern"      => ""                 ,
                                                             "logo"                => "0"                ,
                                                             "logoUrl"             => ""                 ,
                                                             "description"         => ""                 ),
                                             "fr"  => array( "name"                => "Paiement en espèces",
                                                             "infoPageIntern"      => ""                 ,
                                                             "infoPageExtern"      => ""                 ,
                                                             "logo"                => "0"                ,
                                                             "logoUrl"             => ""                 ,
                                                             "description"         => ""                 ),
                                             "es"  => array( "name"                => "Pago en metálico" ,
                                                             "infoPageIntern"      => ""                 ,
                                                             "infoPageExtern"      => ""                 ,
                                                             "logo"                => "0"                ,
                                                             "logoUrl"             => ""                 ,
                                                             "description"         => ""                 ) );

    const LANG_INDEPENDENT_SETTINGS = array( "infoPageType"      ,
                                             "shippingCountries" ,
                                             "feeDomestic"       ,
                                             "feeForeign"        );

    const AVAILABLE_LANGUAGES = array( "de",
                                       "en",
                                       "fr",
                                       "es" );

    const DEFAULT_LANGUAGE = "de";

    const MODEL_NAMESPACE = 'PayUponPickup\Models\Settings';


    public $id;
    public $plentyId;
    public $lang        = '';
    public $name        = '';
    public $value       = '';
    public $updatedAt   = '';


    /**
     * @return string
     */
    public function getTableName():string
    {
        return 'PayUponPickup::settings';
    }
}
