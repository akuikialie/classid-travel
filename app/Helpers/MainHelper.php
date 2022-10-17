<?php

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
     * @param array $array
     * @return
     */
    function createNewVA(string $type = 'tabungan', string $lastVA = null)
    {
        /*
            format va
                1. 857600.0.2209.00001 : tabungan
                2. 857600.1.2209.00001 : paket
        */

        $time_now = Carbon::now();
        $start_of_month = Carbon::now()->startOfMonth();
        $monthDate = substr($time_now->format('Ym'), 2);

        $count = null;

        if ($time_now->toDateString() == $start_of_month->toDateString()) {
            // today is a start of month
            if (empty($lastVA) && $lastVA === null) {
                /* reset count */
                $count = 10000;
            }
        } else {
            if (isset($lastVA) && !empty($lastVA)) {
                $count = (int)collect(explode('.', $lastVA))->last();
            }
        }


        switch ($type) {
            case 'tabungan':
                /* format va */
                $outFormat = '857600.0.' . $monthDate . '.' . ((isset($count) && !empty($count)) ? ($count + 1) : 10001);
                break;

            case 'perencanaan':
                $outFormat = '857600.1.' . $monthDate . '.' .  ((isset($count) && !empty($count)) ? ($count + 1) : 10001);

                break;

            default:
                throw new InvalidArgumentException('Invalid parameter type!.');
                break;
        }

        return $outFormat;
    }
}



