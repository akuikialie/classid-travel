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
use Illuminate\Support\ViewErrorBag;
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
     * @param string|\DateTimeInterface|null $datetime
     * @param \DateTimeZone|string|null      $timezone
     * @param string|null                    $locale
     *
     * @return Carbon
     */
    function carbon(string|DateTimeInterface|null $datetime = null, string|DateTimeZone|null $timezone = null, ?string $locale = null): Carbon
    {
        if (auth()->check()) {
            if (!$timezone && auth()->user()?->timezone) {
                $timezone = auth()->user()->timezone;
            }
            if (!$locale && auth()->user()?->locale) {
                $locale = auth()->user()->locale;
            }
        }

        Carbon::setLocale($locale ?? 'id_ID');
        if (!$datetime) {
            return Carbon::now()->timezone($timezone);
        }
        return Carbon::parse($datetime)->timezone($timezone);
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

if (! function_exists('setDefaultRequest')) {
    /**
     * Set Default Value for Request Input.
     *
     * @param string|array $name
     * @param null         $value
     * @param bool         $force
     */
    function setDefaultRequest(string|array $name, mixed $value = null, bool $force = true): void
    {
        try {
            $request = app('request');

            if (is_array($name)) {
                $data = $name;
            } else {
                $data = [$name => $value];
            }

            if ($force) {
                $request->merge($data);
            } else {
                $request->mergeIfMissing($data);
            }
            $request->session()->flashInput($data);
        } catch (Exception $e) {
            // throw $e;
        }
    }
}

if (! function_exists('hasRoute')) {
    /**
     * Existing Route by Name.
     *
     * @param  string  $name
     * @return bool
     */
    function hasRoute(string $name): bool
    {
        return app('router')->has($name);
    }
}

if (! function_exists('routed')) {
    /**
     * Existing Route by Name
     * with '#' fallback.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function routed(string $name, array $parameters = [], bool $absolute = true): string
    {
        if (app('router')->has($name)) {
            return app('url')->route($name, $parameters, $absolute);
        }

        return '#';
    }
}

if (! function_exists('activeRoute')) {
    function activeRoute(string $route = '', array $params = [], string $cssClass = 'active current'): string
    {
        if (empty($route = trim($route))) {
            return '';
        }

        if (request()->routeIs("{$route}*")) {
            if (empty($params)) {
                return $cssClass;
            }

            $requestRoute = request()->route();

            foreach ($params as $key => $value) {
                if (
                    $requestRoute->parameter($key) instanceof \Illuminate\Database\Eloquent\Model
                    && $value instanceof \Illuminate\Database\Eloquent\Model
                    && $requestRoute->parameter($key)->id != $value->id
                ) {
                    return '';
                }
                if ($requestRoute->parameter($key) != $value) {
                    return '';
                }
            }

            return $cssClass;
        }

        return '';
    }
}

if (! function_exists('inputFeedbackComponent'))
{
    /**
     * Input feedback component
     *
     * @param string|array $message
     * @param string       $mode valid|invalid
     * @param string       $type feedback|tooltip
     * @param string       $glue
     * @param string|null  $id
     *
     * @return string
     */
    function inputFeedbackComponent(string|array $message, string $mode = 'invalid', string $type = 'feedback', string $glue = '<br>', ?string $id = null): string
    {
        if (!in_array($mode, ['valid', 'invalid'])) {
            $mode = 'invalid';
        }
        if (!in_array($type, ['feedback', 'tooltip'])) {
            $type = 'feedback';
        }
        return '<div class="'. $mode .'-' . $type . '"'.($id ? " id=\"{$id}\"" : '').'>'.(is_array($message) ? implode($glue, $message) : $message).'</div>';
    }
}

if (! function_exists('getErrors'))
{
    /**
     * Feedback CSS Class
     *
     * @param string|null $key
     * @param string|null $bag
     *
     * @return ?ViewErrorBag
     */
    function getErrors(?string $key = null, ?string $bag = null): ?ViewErrorBag
    {
        $errors = session('errors');
        if (empty($key) || empty($errors)) return null;
        if ($bag) {
            if (empty($errors->$bag->all())) return null;
            $errors = $errors->$bag;
        }

        return $errors;
    }
}

if (! function_exists('hasError'))
{
    /**
     * Feedback CSS Class
     *
     * @param string|array|null $key
     * @param string|null $bag
     *
     * @return bool
     */
    function hasError(string|array|null $key = null, ?string $bag = null): bool
    {
        if (($errors = getErrors($key, $bag)) instanceof ViewErrorBag === false) return false;
        return $errors->has($key);
    }
}

if (! function_exists('errorCss'))
{
    /**
     * Feedback CSS Class
     *
     * @param string|array|null $key
     * @param string|null       $bag
     * @param bool              $isGroup
     * @param string|null       $class
     *
     * @return string
     */
    function errorCss(string|array|null $key = null, ?string $bag = null, bool $isGroup = false, ?string $class = null): string
    {
        if (hasError($key, $bag)) {
            return $class ?? ($isGroup ? 'has-error' : 'is-invalid');
        }
        return '';
    }
}

if (! function_exists('inputFeedback'))
{
    /**
     * InValid input feedback
     *
     * @param string|array|null $key
     * @param ?string           $bag
     * @param string            $type feedback|tooltip
     *
     * @return string
     */
    function inputFeedback(string|array|null $key = null, ?string $bag = null, string $type = 'feedback'): string
    {
        if (empty($errors = getErrors($key, $bag))) return '';

        if (is_array($key)) {
            $messages = [];
            foreach ($key as $k) {
                if ($errors->has($k)) {
                    $messages[] = $errors->first($k);
                }
            }

            return !empty($messages) ? inputFeedbackComponent($messages, 'invalid', $type) : '';
        }

        return $errors->has($key) ? inputFeedbackComponent($errors->first($key), 'invalid', $type) : '';
    }
}

if (! function_exists('errorAll'))
{
    /**
     * InValid input feedback
     *
     * @param string|null $bag
     * @param array|null $excludeKey
     * @return string
     */
    function errorAll(string $bag = null, array $excludeKey = null): string
    {
        $errors = session('errors');
        if (empty($errors)) return '';
        if ($bag) {
            if (empty($errors->$bag->all())) return '';
//            $errors = $errors->$bag;
        }
//        if (!$errors->has($key)) return '';

        return '<div class="alert alert-danger rounded-0" style="border-width: 2px; border-left: none; border-right: none;">' .
            '<h4 class="alert-heading">Eror!! <small>Periksa Lagi Inputan Anda</small></h4>' .
        '</div>';
    }
}

if (!function_exists('paginateStyleReset')) {
    /**
     * Style reset paginate
     *
     * @param $datas
     *
     * @return string
     */
    function paginateStyleReset($datas): string
    {
        try {
            if (method_exists($datas, 'perPage') && method_exists($datas, 'currentPage')) {
                return 'counter-reset: _rownum ' . ($datas->perPage() * ($datas->currentPage() - 1)) . ';';
            }
        } catch (Exception $e) {}
        return '';
    }
}

if (!function_exists('logError')) {
    /**
     * @param string|\Exception $exception
     * @param string|null $title
     * @param string|array|null $data
     *
     * @return void
     */
    function logError(string|Exception $exception, ?string $title = null, string|array|null $data = null): void
    {
        if ($title) {
            app('log')->error("=====#   {$title}   #=====");
        }

        if ($data) {
            $data = json_encode($data, JSON_PRETTY_PRINT);
        }

        if (is_string($exception)) {
            app('log')->error("message : {$exception}");
            app('log')->error("code : 4xx");
            if ($data) {
                app('log')->error("data : {$data}");
            }
        }

        if ($exception instanceof Exception) {
            app('log')->error("message : " . $exception->getMessage());
            app('log')->error("code : " . $exception->getCode());
            app('log')->error("file : " . $exception->getFile());
            app('log')->error("line : " . $exception->getLine());
            if ($data) {
                app('log')->error("data : {$data}");
            }
            app('log')->error("trace :\n" . $exception->getTraceAsString());
        }

        app('log')->error("===== ===== ===== ===== ===== ===== ===== ===== ===== =====\n");
    }
}
