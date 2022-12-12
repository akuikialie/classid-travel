<?php

use App\Models\Tenant\Tenant;
use Carbon\Carbon;

if (!function_exists('array_to_object')) {

    /**
     * Convert Array into Object in deep
     *
     * @param array $array
     * @return
     */
    function array_to_object($array)
    {
        return json_decode(json_encode($array));
    }
}

if (!function_exists('rupiahFormat')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function rupiahFormat(int $amount, $precision = 1)
    {
        if ($amount < 900) {
            $format = number_format($amount, $precision);
            $symbol = '';
        } else if ($amount < 900000) {
            $format = number_format($amount / 1000, $precision);
            $symbol = 'rb';
        } else if ($amount < 900000000) {
            $format = number_format($amount / 1000000, $precision);
            $symbol = 'jt';
        } else if ($amount < 900000000000) {
            $format = number_format($amount / 1000000000, $precision);
            $symbol = 'M';
        } else {
            $format = number_format($amount / 1000000000000, $precision);
            $symbol = 'T';
        }

        if ($precision > 0) {
            $separator = '.' . str_repeat('0', $precision);
            $format = str_replace($separator, '', $format);
        }

        return $format . $symbol;
    }
}

if (!function_exists('createNewVA')) {

    /**
     * Convert Array into Object in deep
     *
     * @param string $type
     * @param string|null $lastVA
     * @return int
     */
    function createNewVA(string $type = 'tabungan', ?string $lastVA = null): int
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

            $getBcn = substr($lastVA, '0', 6);
            $getType = $type == 'tabungan' ? 0 : 1;
            $getMonthYear = substr($lastVA, '7', 4);
            $increment = substr($lastVA, '11') ?? 0;

            if ($time_now->toDateString() == $start_of_month->toDateString()) {
                // today is a start of month
                $count = 1;
                $month = $monthDate;
            } else {
                $count = $increment + 1;
                $month = $getMonthYear;
            }

            $getIncrement = str_pad($count, '5', '0', STR_PAD_LEFT);
            return $getBcn.$getType.$month.$getIncrement;
        }else{
            $getBcn = config('wallet.bcn');
            $getType = $type == 'tabungan' ? 0 : 1;
            $getIncrement = str_pad(1, '5', '0', STR_PAD_LEFT);
            return $getBcn.$getType.$monthDate.$getIncrement;
        }
    }
}



