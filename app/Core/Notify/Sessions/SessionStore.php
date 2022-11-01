<?php

namespace App\Core\Notify\Sessions;

interface SessionStore
{
    /**
     * Flash some data into the session.
     *
     * @param $name
     * @param $data
     */
    public function flash($name, $data);
}
