<?php
namespace Base\App\PartnerManager;

use Illuminate\Support\Arr;
use phpDocumentor\Reflection\Types\Mixed_;

class PartnerManager
{
    private static array $available = ['cid', 'bmi', 'bsi', 'bukopin', 'cimb'];

    /**
     * @var string
     */
    private static string $partner;

    private static array $partnerData = [];

    /**
     * Class Constructor
     */
    public function __construct()
    {
        if (empty(self::$partner) || empty(self::$partnerData)) {
            static::init();
        }
    }

    /**
     * @param string|null $partner
     *
     * @return void
     */
    public static function init(?string $partner = null): PartnerManager
    {
        if (!empty($partner)) {
            self::setPartner($partner);
        } else {
            self::setPartner(config('app.partner', 'cid'));
        }

        return new static;
    }

    /**
     * Get Partner Information
     *
     * @return string
     */
    public static function getName(): string
    {
        if (empty(self::$partner)) self::setPartner();
        return self::$partner;
    }

    /**
     * Get Partner Information
     *
     * @return array
     */
    public static function getInfo(): array
    {
        if (empty(self::$partner)) self::setPartner();
        return self::$partnerData;
    }

    /**
     * @param string $key
     * @param null|array|string   $default
     *
     * @return array|string
     */
    public static function getData(string $key, $default = null)
    {
        return Arr::get(static::$partnerData, $key, $default) ?? '';
    }

    /**
     * @param string|null           $key
     * @param array|string|null     $default
     *
     * @return array|string
     */
    public static function data(?string $key = null, $default = null)
    {
        if (empty($key)) return static::getInfo();

        return static::getData($key, $default);
    }

    // == PRIVATE METHODS == //

    /**
     * @param string $partner
     *
     * @return void
     */
    private static function setPartner(string $partner = 'cid'): void
    {
        $partner = trim($partner);
        $partnerFile = __DIR__ . "/data/{$partner}.php";

        self::$partner = file_exists($partnerFile) ? $partner : 'cid';
        if (empty(self::$partnerData)) {
            self::$partnerData = include_once('data/' . self::$partner . '.php');
        }
    }

}
