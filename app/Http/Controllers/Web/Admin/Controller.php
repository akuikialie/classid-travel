<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller as BaseController;
use Exception;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole() && !(env(key: 'ADMIN_URL') === request()->host())) {
//            abort(404);
        }
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     * @throws \Exception
     */
    protected function view(string $view): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $colors = activeTenant()?->tenantData()->whereIn('key', ['sidebar_color', 'logo_color', 'font_color'])->get() ?? collect();
        $this->setData('sidebarColor', $colors?->first(fn($it) => ($it?->key ?? '') == 'sidebar_color')?->value ?? '#F2E1FE');
        $this->setData('logoColor', $colors?->first(fn($it) => ($it?->key ?? '') == 'logo_color')?->value ?? '#611E91');
        $this->setData('fontColor', $colors?->first(fn($it) => ($it?->key ?? '') == 'font_color')?->value ?? '#000');

        return parent::view($view);
    }
}
