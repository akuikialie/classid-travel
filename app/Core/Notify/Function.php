<?php

if (!function_exists('notify')) {

    /**
     * Return app instance of Alert.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    function notify($title = '', $message = '', $type = '')
    {
        $notify = app('notify');
        if (!is_null($title)) {
            return $notify->notify($title, $message, $type);
        }
        return $notify;
    }
}

if (!function_exists('toast')) {

    /**
     * Return app instance of Toast.
     *
     * @param string $title
     * @param string $type
     * @param string $position
     */
    function toast($title = '', $type = null, $position = 'top-right')
    {
        $notify = app('notify');
        if (!is_null($title)) {
            return $notify->toast($title, $type, $position);
        }
        return $notify;
    }
}
