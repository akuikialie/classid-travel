<?php
namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait ViewSupport
{
    /**
     * @param string $parameter
     * @param $value
     * @return $this
     */
    public function setGlobalParams(string $parameter, $value): static
    {
        $this->shareGlobalParams($parameter, $value);
        return $this;
    }

    /**
     * @param string $argument
     * @param $value
     * @return void
     */
    public function shareGlobalParams(string $argument, $value): void
    {
        view()->share($argument, $value);
    }

    public function registerBreadcrumbs(string $title): void
    {
        $this->bind(ucwords(($title)));
    }

    public function bind(string $title): void
    {
        $uriPath = explode('/', Route::getCurrentRoute()->uri());
        $pathSections = [];
        foreach ($uriPath as $path) {
            $startWith = Str::startsWith($path, '{');
            $endWith = Str::endsWith($path, '}');
            if ($startWith && $endWith){
                continue;
            }

            $url = (Route::has("{$path}.index") ? \route("{$path}.index") : null);

            $pathSections[] = [
                'name' => ucfirst($path),
                'url' => $url,
            ];
        }

        view()->share('breadcrumbs', array_to_object([
            'title' => $title,
            'sections' => $pathSections,
        ]));
    }
}
