<?php

/**
 * List of helpers collection.
 *
 * @author      yusron arif <yusron.arif4@gmail.com>
 */

use App\Models\Tenant\Tenant;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

if (!function_exists('activeTenant')) {
    /**
     * @return Tenant|Builder|Model|null
     */
    function activeTenant(): Model|Builder|Tenant|null
    {
        return app('activeTenant');
    }
}

if (!function_exists('isActiveTenant')) {
    /**
     * @return bool
     */
    function isActiveTenant(): bool
    {
        return app('activeTenant') instanceof Tenant;
    }
}

if (!function_exists('isMobile')) {
    /**
     * @return bool
     * @throws BindingResolutionException
     */
    function isMobile(): bool
    {
        return in_array(request()->header('User-Agent'), ['x-cid-mobile', 'my-class-android-app']);
    }
}

if (!function_exists('isNativeMobile')) {
    /**
     * @param null|string $type
     *
     * @return bool
     * @throws BindingResolutionException
     */
    function isNativeMobile(?string $type = null): bool
    {
        $head = request()->header('x-mobile');
        return !$type ? in_array($head, ['android', 'ios']) : $head == $type;
    }
}

if (!function_exists('isProduction')) {
    /**
     * @return bool
     * @throws BindingResolutionException
     */
    function isProduction(): bool
    {
        return in_array(app()->environment(), ['prod', 'production']);
    }
}

if (!function_exists('isNonProduction')) {
    /**
     * @return bool
     * @throws BindingResolutionException
     */
    function isNonProduction(): bool
    {
        return !isProduction();
    }
}

if (!function_exists('debugNonProduction')) {
    /**
     * @return bool
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function debugNonProduction(): bool
    {
        return config('app.debug') && isNonProduction();
    }
}

if (!function_exists('moneyFormat')) {
    /**
     * @param      $integer
     * @param bool $dotNotation
     *
     * @return string
     */
    function moneyFormat($integer, bool $dotNotation = false): string
    {
        if ($dotNotation) {
            $decimals = explode(".", $integer);
            if (count($decimals) == 2) {
                return number_format($decimals[0], 0, ',', '.') . "," . $decimals[1];
            } else {
                return number_format($integer, 0, ',', '.');
            }
        } else {
            $decimals = explode(",", $integer);
            if (count($decimals) == 2) {
                return number_format($decimals[0], 0, '.', ',') . "." . $decimals[1];
            } else {
                return number_format($integer, 0, '.', ',');
            }
        }
    }
}

if (!function_exists('trimAll')) {
    /**
     * @param null|string $string
     * @param string $type
     * @param string $pattern
     *
     * @return string
     * @throws Exception
     */
    function trimAll(?String $string, String $type = 'smart', String $pattern = '\W+'): String
    {
        if (!$string || trim($string)=='') return '';
        if (! in_array($type, ['smart', 'both', 'left', 'right', 'all']))
            throw new Exception("type of trim not valid, use smart|left|right|all instead.", 401);

        try {
            switch ($type) {
                case 'both':
                    return preg_replace('/^'. $pattern .'|'. $pattern .'$/i', '', $string);
                case 'left':
                    return preg_replace('/^'. $pattern .'/i', '', $string);
                case 'right':
                    return preg_replace('/'. $pattern .'$/i', '', $string);
                case 'all':
                    return preg_replace('/'. $pattern .'/i', '', $string);
                default:
                    return preg_replace('/'. $pattern .'/i', ' ', preg_replace('/^'. $pattern .'|'. $pattern .'$/i', '', $string));
            }
        } catch (Exception $e) {}

        return '';
    }
}

if (!function_exists('carbon')) {

    /**
     * @param string|DateTimeInterface|null $datetime
     * @param string|DateTimeZone|null $timezone
     * @param string|null $locale
     * @return Carbon
     */
    function carbon(string|DateTimeInterface|null $datetime = null, string|DateTimeZone|null $timezone = 'Asia/Jakarta', ?string $locale = null): Carbon
    {
        Carbon::setLocale($locale ?? 'id_ID');
        if (!$datetime) {
            return Carbon::now($timezone);
        }

        return Carbon::parse($datetime, $timezone);
    }
}

if (!function_exists('numberFormat')) {
    /**
     * @param mixed $integer
     *
     * @return string
     */
    function numberFormat($integer): string
    {
        return number_format($integer, 0, ',', '.');
    }
}

if (!function_exists('romanicNumber')) {
    /**
     * @param $integer
     * @param $upcase
     *
     * @return string
     */
    function romanicNumber($integer, $upcase = true): string
    {
        $table = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}
if (!function_exists('romanicToInt')) {
    /**
     * @param $romanic
     *
     * @return int|null
     * @throws Exception
     */
    function romanicToInt($romanic): ?int
    {
        $romans = [
            'M'   => 1000,
            'CM'  => 900,
            'D'   => 500,
            'CD'  => 400,
            'C'   => 100,
            'XC'  => 90,
            'L'   => 50,
            'XL'  => 40,
            'X'   => 10,
            'IX'  => 9,
            'V'   => 5,
            'IV'  => 4,
            'I'   => 1
        ];

        $roman = strtoupper(trimAll($romanic, 'all'));
        $result = null;

        foreach ($romans as $key => $value) {
            while (strpos($roman, $key) === 0) {
                $result += $value;
                $roman = substr($roman, strlen($key));
            }
        }
        return $result;
    }

    if (!function_exists('numberSpell')) {
        /**
         * Spell number to words.
         *
         * @param $value
         * @return string
         */
        function numberSpell($value)
        {
            $f = new NumberFormatter('id', NumberFormatter::SPELLOUT);

            return $f->format($value);
        }
    }
}
