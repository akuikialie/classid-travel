<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole() && !(env(key: 'ADMIN_URL') === request()->host())) {
            abort(404);
        }
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $view
     * @return \Illuminate\Contracts\View\View|Factory
     */
    protected function view(string $view)
    {
        $tenant = activeTenant();
        $this->setData('sidebarColor', $tenant?->tenantData?->where('key', 'sidebar_color')->first()?->value ?? '#F2E1FE');
        $this->setData('logoColor', $tenant?->tenantData?->where('key', 'logo_color')->first()?->value ?? '#611E91');
        $this->setData('fontColor', $tenant?->tenantData?->where('key', 'font_color')->first()?->value ?? '#F2E1FE');


        return parent::view($view);
    }
}
