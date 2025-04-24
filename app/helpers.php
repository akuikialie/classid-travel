<?php

use App\Core\Whatsapp\Messages\WhatsappMessage;
use App\Enums\GenerateNumberType;
use App\Exceptions\ApiDumpException;
use App\Models\Tenant\Tenant;
use App\Services\GenerateNumber\GenerateNumberService;
use App\Services\Notification\NotifManager;
use Classid\TemplateReplacement\TemplateReplacement;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\ViewErrorBag;


function generateVirtualNumber(Tenant $tenant, bool $isLocking = false, int $numberGenerated = 1): array
{
    $generateNumber = (new GenerateNumberService())
        ->getNextGenerateNumber(
            tenant: $tenant,
            type: GenerateNumberType::VIRTUAL_NUMBER->value,
            isTransactional: $isLocking,
            numberGenerated: $numberGenerated
        );

    $cardNumbers = collect();
    foreach ($generateNumber->generated_numbers as $generated_number) {
        $uniqueReplacement = TemplateReplacement::execute(
            templatePattern: $generateNumber->number_pattern,
            priorityReplacementData: [
                "tenant_bcn" => $tenant->bcn,
                "month_year" => now()->format('ym'),
            ]
        );

        $newNumber = numberSignReplacement($uniqueReplacement, $generated_number);
        $cardNumbers->push($newNumber);
    }

    return $cardNumbers->toArray();
}

function generateInvoiceNumber(Tenant $tenant, bool $isLocking = false, int $numberGenerated = 1): array
{
    $generateNumber = (new GenerateNumberService())
        ->getNextGenerateNumber(
            tenant: $tenant,
            type: GenerateNumberType::INVOICE_NUMBER->value,
            isTransactional: $isLocking,
            numberGenerated: $numberGenerated
        );

    $cardNumbers = collect();
    foreach ($generateNumber->generated_numbers as $generated_number) {
        $uniqueReplacement = TemplateReplacement::execute(
            templatePattern: $generateNumber->number_pattern,
            priorityReplacementData: [
                "month_year" => now()->format('ym'),
            ]
        );

        $newNumber = numberSignReplacement($uniqueReplacement, $generated_number);
        $cardNumbers->push($newNumber);
    }

    return $cardNumbers->toArray();
}

function generateTransactionNumber(Tenant $tenant,\App\Enums\TransactionType $transactionType, bool $isLocking = false, int $numberGenerated = 1): array
{
    $generateNumber = (new GenerateNumberService())
        ->getNextGenerateNumber(
            tenant: $tenant,
            type: GenerateNumberType::TRANSACTION_NUMBER->value,
            isTransactional: $isLocking,
            numberGenerated: $numberGenerated
        );

    $cardNumbers = collect();
    foreach ($generateNumber->generated_numbers as $generated_number) {
        $uniqueReplacement = TemplateReplacement::execute(
            templatePattern: $generateNumber->number_pattern,
            priorityReplacementData: [
                "trx_type" => $transactionType->code(),
                "month_year" => now()->format('ym'),
            ]
        );

        $newNumber = numberSignReplacement($uniqueReplacement, $generated_number);
        $cardNumbers->push($newNumber);
    }

    return $cardNumbers->toArray();
}

/**
 * @param string $pattern
 * @param string $numberToGenerate
 *
 * @return string
 */
function numberSignReplacement(string $pattern, string $numberToGenerate): string
{
    preg_match('/#{3,}\z/', $pattern, $matches);
    $numberDigit = strlen($matches[0]);
    $fixedCharacter = str_replace($matches[0], '', $pattern);

    $digitReplacement = str_pad($numberToGenerate, $numberDigit, '0', STR_PAD_LEFT);

    return $fixedCharacter . $digitReplacement;
}

if (!function_exists('extract_filters')) {
    /**
     * @param array $filters
     * @return array
     */
    function extract_filters(array $filters): array
    {
        $newFilters = [];
        foreach ($filters as $filter) {
            if ($filter['name'] === '_token') {
                continue;
            }
            $newFilters[$filter['name']] = $filter['value'];
        }
        return $newFilters;
    }
}

if (!function_exists('tableHashId')) {
    function tableHashId(
        \Illuminate\Database\Schema\Blueprint $table,
        string|null                           $column = 'hashid',
        int                                   $length = 50
    ): void
    {
        $table->string($column, $length)->nullable();
    }
}

if (!function_exists('tableTenantId')) {
    function tableTenantId(
        \Illuminate\Database\Schema\Blueprint $table,
        string|null                           $column = 'tenant_id'
    ): void
    {
        $table->foreignId($column)->nullable()
            ->constrained('tenants')
            ->onDelete('restrict');
    }
}

if (!function_exists('tableUserId')) {
    function tableUserId(
        \Illuminate\Database\Schema\Blueprint $table,
        string|null                           $column = 'user_id'
    ): void
    {
        $table->foreignId($column)->nullable()
            ->constrained('users')
            ->onDelete('restrict');
    }
}

if (!function_exists('tableTimestamps')) {
    function tableTimestamps(
        \Illuminate\Database\Schema\Blueprint $table,
        int                                   $precision = 0,
        bool                                  $constrained = true
    ): void
    {
        $table->timestampsTz(precision: $precision);
        if ($constrained) {
            $table->foreignId('created_by')->nullable()
                ->comment('reference to users_table')
                ->constrained('users')
                ->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()
                ->comment('reference to users_table')
                ->constrained('users')
                ->onDelete('restrict');
        } else {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        }
    }
}

/**
 * @param mixed $data
 * @return mixed
 * @throws ApiDumpException
 */
function ddapi(mixed $data)
{
    throw new ApiDumpException($data);
}


if (!function_exists('tableSoftDeletes')) {
    function tableSoftDeletes(
        \Illuminate\Database\Schema\Blueprint $table,
        string                                $column = 'deleted_at',
        int                                   $precision = 0,
        bool                                  $constrained = true
    ): void
    {
        $table->softDeletesTz($column, precision: $precision);
        if ($constrained) {
            $table->foreignId('deleted_by')->nullable()
                ->comment('reference to users_table')
                ->constrained('users')
                ->onDelete('restrict');
        } else {
            $table->unsignedBigInteger('deleted_by')->nullable();
        }
    }
}

if (!function_exists('activeTenant')) {
    /**
     * @return \App\Models\Tenant\Tenant|null
     */
    function activeTenant(): Tenant|null
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
     */
    function isMobile(): bool
    {
        return in_array(request()->header('User-Agent'), ['x-mobile', 'x-cid-mobile', 'x-cid-android', 'x-cid-ios']);
    }
}

if (!function_exists('isNativeMobile')) {
    /**
     * @param null|string $type
     *
     * @return bool
     */
    function isNativeMobile(string|null $type = null): bool
    {
        $head = request()->header('x-mobile');
        return !$type ? in_array($head, ['android', 'ios']) : $head == $type;
    }
}

if (!function_exists('isProduction')) {
    /**
     * @return bool
     */
    function isProduction(): bool
    {
        return app()->isProduction();
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

if (!function_exists('isDevelopmentMode')) {
    /**
     * @return bool
     */
    function isDevelopmentMode(): bool
    {
        return in_array(app()->environment(), ['local', 'demo']);
    }
}

if (!function_exists('debugNonProduction')) {
    /**
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function debugNonProduction(): bool
    {
        return config('app.debug') && isNonProduction();
    }
}

if (!function_exists('moneyFormat')) {
    /**
     * @param int|float $amount
     * @param bool $dotThousand
     *
     * @return string
     */
    function moneyFormat(int|float $amount, bool $dotThousand = true): string
    {
        $separatorThousand = ',';
        $separatorDecimal = '.';
        $decimal = is_float($amount) ? 2 : 0;
        if ($dotThousand) {
            $separatorThousand = '.';
            $separatorDecimal = ',';
        }

        return number_format($amount, $decimal, $separatorDecimal, $separatorThousand);
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
    function trimAll(string|null $string, string $type = 'smart', string $pattern = '\W+'): string
    {
        if (!$string || trim($string) === '') return '';
        if (!in_array($type, ['smart', 'both', 'left', 'right', 'all']))
            throw new Exception("type of trim not valid, use smart|left|right|all instead.", 401);

        try {
            return match ($type) {
                'both' => preg_replace('/^' . $pattern . '|' . $pattern . '$/i', '', $string),
                'left' => preg_replace('/^' . $pattern . '/i', '', $string),
                'right' => preg_replace('/' . $pattern . '$/i', '', $string),
                'all' => preg_replace('/' . $pattern . '/i', '', $string),
                default => preg_replace('/' . $pattern . '/i', ' ', preg_replace('/^' . $pattern . '|' . $pattern . '$/i', '', $string)),
            };
        } catch (Exception) {
        }

        return '';
    }
}

if (!function_exists('carbon')) {
    /**
     * @param string|DateTimeInterface|null $datetime
     * @param DateTimeZone|string|null $timezone
     * @param string|null $locale
     *
     * @return Carbon
     */
    function carbon(string|DateTimeInterface|null $datetime = null, string|DateTimeZone|null $timezone = null, string|null $locale = null): Carbon
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!$timezone && $user?->timezone) {
                $timezone = $user->timezone;
            }
            if (!$locale && $user?->locale) {
                $locale = $user->locale;
            }
        }

        Carbon::setLocale($locale ?? 'id_ID');
        if (!$datetime) {
            return Carbon::now()->timezone($timezone);
        }
        return Carbon::parse($datetime)->timezone($timezone);
    }
}

if (!function_exists('msnotif')) {
    /**
     * @param string $channel
     * @param int|null $schoolId
     *
     * @return NotifManager
     */
    function msnotif(string $channel, int|null $schoolId = null): NotifManager
    {
        return new NotifManager($channel, $schoolId);
    }
}

if (!function_exists('whatsappMessage')) {
    /**
     * @param string|null $key
     *
     * @return WhatsappMessage
     * @throws Exception
     */
    function whatsappMessage(string|null $key = null): WhatsappMessage
    {
        return new WhatsappMessage($key);
    }
}

if (!function_exists('numberFormat')) {
    /**
     * @param mixed $integer
     *
     * @return string
     */
    function numberFormat(int|float $integer): string
    {
        return number_format($integer, 0, ',', '.');
    }
}

if (!function_exists('intToRoman')) {
    /**
     * @param int $number
     * @param bool $upper
     *
     * @return string
     */
    function intToRoman(int $number, bool $upper = true): string
    {
        $romans = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100,
            'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10,
            'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        $return = '';
        while ($number > 0) {
            foreach ($romans as $rom => $arb) {
                if ($number >= $arb) {
                    $number -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}
if (!function_exists('romanToInt')) {
    /**
     * @param string $roman
     *
     * @return int|null
     * @throws \Exception
     */
    function romanToInt(string $roman): int|null
    {
        $romans = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100,
            'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10,
            'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];

        $roman = strtoupper(trimAll($roman, 'all'));
        $result = null;

        foreach ($romans as $key => $value) {
            while (str_starts_with($roman, $key)) {
                $result += $value;
                $roman = substr($roman, strlen($key));
            }
        }
        return $result;
    }
}

if (!function_exists('numberSpell')) {
    /**
     * Spell number to words.
     *
     * @param $value
     *
     * @return string
     */
    function numberSpell($value): string
    {
        $f = new NumberFormatter('id', NumberFormatter::SPELLOUT);

        return $f->format($value);
    }
}

if (!function_exists('setDefaultRequest')) {
    /**
     * Set Default Value for Request Input.
     *
     * @param string|array $name
     * @param null $value
     * @param bool $force
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
        } catch (Exception) {
        }
    }
}

if (!function_exists('hasRoute')) {
    /**
     * Existing Route by Name.
     *
     * @param string $name
     *
     * @return bool
     */
    function hasRoute(string $name): bool
    {
        return app('router')->has($name);
    }
}

if (!function_exists('routed')) {
    /**
     * Existing Route by Name
     * with '#' fallback.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     *
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

if (!function_exists('activeRoute')) {
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
                    $requestRoute->parameter($key) instanceof Model
                    && $value instanceof Model
                    && $requestRoute->parameter($key)->id !== $value->id
                ) {
                    return '';
                }
                if ($requestRoute->parameter($key) !== $value) {
                    return '';
                }
            }

            return $cssClass;
        }

        return '';
    }
}

if (!function_exists('inputFeedbackComponent')) {
    /**
     * Input feedback component
     *
     * @param string|array $message
     * @param string $mode valid|invalid
     * @param string $type feedback|tooltip
     * @param string $glue
     * @param string|null $id
     *
     * @return string
     */
    function inputFeedbackComponent(string|array $message, string $mode = 'invalid', string $type = 'feedback', string $glue = '<br>', string|null $id = null): string
    {
        if (!in_array($mode, ['valid', 'invalid'])) {
            $mode = 'invalid';
        }
        if (!in_array($type, ['feedback', 'tooltip'])) {
            $type = 'feedback';
        }
        return '<div class="' . $mode . '-' . $type . '"' . ($id ? " id=\"{$id}\"" : '') . '>' . (is_array($message) ? implode($glue, $message) : $message) . '</div>';
    }
}

if (!function_exists('getErrors')) {
    /**
     * Feedback CSS Class
     *
     * @param string|null $key
     * @param string|null $bag
     *
     * @return ViewErrorBag|null
     */
    function getErrors(string|null $key = null, string|null $bag = null): ViewErrorBag|null
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

if (!function_exists('hasError')) {
    /**
     * Feedback CSS Class
     *
     * @param string|array|null $key
     * @param string|null $bag
     *
     * @return bool
     */
    function hasError(string|array|null $key = null, string|null $bag = null): bool
    {
        if (($errors = getErrors($key, $bag)) instanceof ViewErrorBag === false) return false;
        return $errors->has($key);
    }
}

if (!function_exists('errorCss')) {
    /**
     * Feedback CSS Class
     *
     * @param string|array|null $key
     * @param string|null $bag
     * @param bool $isGroup
     * @param string|null $class
     *
     * @return string
     */
    function errorCss(string|array|null $key = null, string|null $bag = null, bool $isGroup = false, string|null $class = null): string
    {
        if (hasError($key, $bag)) {
            return $class ?? ($isGroup ? 'has-error' : 'is-invalid');
        }
        return '';
    }
}

if (!function_exists('inputFeedback')) {
    /**
     * InValid input feedback
     *
     * @param string|array|null $key
     * @param string|null $bag
     * @param string $type feedback|tooltip
     *
     * @return string
     */
    function inputFeedback(string|array|null $key = null, string|null $bag = null, string $type = 'feedback'): string
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

if (!function_exists('errorAll')) {
    /**
     * InValid input feedback
     *
     * @param string|null $bag
     * @param array|null $excludeKey
     *
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
     * @param \Illuminate\Database\Eloquent\Collection $datas
     *
     * @return string
     */
    function paginateStyleReset(\Illuminate\Database\Eloquent\Collection $datas): string
    {
        try {
            if (method_exists($datas, 'perPage') && method_exists($datas, 'currentPage')) {
                return 'counter-reset: _rownum ' . ($datas->perPage() * ($datas->currentPage() - 1)) . ';';
            }
        } catch (Exception $e) {
        }
        return '';
    }
}

if (!function_exists('logError')) {
    /**
     * @param string|Throwable $exception
     * @param string|null $title
     * @param string|array|null $data
     *
     * @return void
     */
    function logError(string|Throwable $exception, string|null $title = null, string|array|null $data = null): void
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

        if ($exception instanceof Throwable) {
            app('log')->error("message : " . $exception->getMessage());
            app('log')->error("code : " . $exception->getCode());
            app('log')->error("file : " . $exception->getFile());
            app('log')->error("line : " . $exception->getLine());
            if ($data) {
                app('log')->error("data : {$data}");
            }
            app('log')->error("trace :\n" . $exception->getTraceAsString());
            toSentry(throw: $exception);
        }


        app('log')->error("===== ===== ===== ===== ===== ===== ===== ===== ===== =====\n");
    }
}

if (!function_exists('partner')) {
    /**
     * @param null|string $key
     * @param null|array|string $default
     * @param string|null $partnerId
     *
     * @return array|string|\Base\App\PartnerManager\PartnerManager
     */
    function partner(string|null $key = null, $default = null, string|null $partnerId = null)
    {
        if (empty($key))
            return \Base\App\PartnerManager\PartnerManager::init($partnerId);

        return \Base\App\PartnerManager\PartnerManager::init($partnerId)->getData($key, $default);
    }
}

if (!function_exists('cachedAsset')) {
    /**
     * @param string $path
     * @param bool|null $secure
     *
     * @return string
     */
    function cachedAsset(string $path, bool|null $secure = null): string
    {
        $asset = str($path)->is('/^https?:\/\//i')
            ? $path
            : asset($path, $secure);

        return $asset . '?v=' . config('cache.version', time());
    }
}

if (!function_exists('createNewVA')) {
    /**
     * Convert Array into Object in deep
     *
     * @param string $type
     * @param string|null $lastVA
     *
     * @return int
     */
    function createNewVA(string $type = 'tabungan', string|null $lastVA = null): int
    {
        /*
            format va00001
                1. 8576000220900001 : tabungan
                2. 857600.1.2209.00001 : paket
        */

        $time_now = Carbon::now();
        $start_of_month = Carbon::now()->startOfMonth();
        $monthDate = substr($time_now->format('Ym'), 2);
        if (isset($lastVA)) {
            $getBcn = activeTenant()?->bcn ?? '';
            $getType = $type == 'tabungan' ? 0 : 1;
            $getMonthYear = substr($lastVA, '7', 4);
            $increment = (int)substr($lastVA, '11') ?? 0;

            if ($time_now->toDateString() == $start_of_month->toDateString()) {
                // today is a start of month
                $count = 1;
                $month = $monthDate;
            } else {
                $count = $increment + 1;
                $month = $getMonthYear;
            }

            $getIncrement = str_pad($count, '5', '0', STR_PAD_LEFT);
            return $getBcn . $getType . $month . $getIncrement;
        } else {
            $getBcn = activeTenant()->bcn;
            $getType = $type == 'tabungan' ? 0 : 1;
            $getIncrement = str_pad(1, '5', '0', STR_PAD_LEFT);
            return $getBcn . $getType . $monthDate . $getIncrement;
        }
    }
}

if (!function_exists('toSentry')) {
    /**
     * @param Throwable $throw
     *
     * @return void
     */
    function toSentry(Throwable $throw): void
    {
        if (app()->bound('sentry') && !app()->isLocal()) {
            \Sentry\Laravel\Integration::captureUnhandledException($throw);
        }
    }
}
