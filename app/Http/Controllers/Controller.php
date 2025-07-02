<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Current view path
     *
     * @var null|string
     */
    protected string|null $viewPath = null;

    /**
     * Active User.
     *
     * @var string|null
     */
    private ?User $activeUser;

    /**
     * Controller data.
     *
     * @var array
     */
    private array $controllerData = [];

    /**
     * Active menu indicator.
     *
     * @var array
     */
    private array $activeMenu = [];

    /**
     * Page title.
     *
     * @var string|null
     */
    private string|null $pageTitle;

    /**
     * Page Meta.
     *
     * @var array
     */
    private array $pageMeta = [
        'description' => null,
        'keywords' => null
    ];

    /**
     * Breadcrumbs Collection.
     *
     * @var Collection
     */
    private Collection $breadCrumbs;

    /**
     * Reserved variable for the controller.
     *
     * @var array
     */
    private array $reservedVariables = ['activeMenu', 'pageTitle', 'pageMeta'];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // dd([
        //     'run-in-console' => app()->runningInConsole(),
        //     'admin-url' => env(key: 'ADMIN_URL'),
        //     'request-host' => request()->host(),
        //     'env-equal-host' => env(key: 'ADMIN_URL') === request()->host(),
        //     'path-start-with-admin' => preg_match('/^admin(\/.*)?/i', request()->path()),
        // ]);
        if (
            !app()->runningInConsole() &&
            hostIsAdmin() &&
            !str(request()->path())->startWith('admin')
        ) {
            return to_route('admin.dashboard')->send();
        }
        $this->setBreadCrumb([]);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $view
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(string $view): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        if (false === array_key_exists('pageTitle', $this->controllerData)) {
            $this->setPageTitle('Untitled');
        }

        $this->setPageMeta('csrf_token', csrf_token());
        $this->controllerData['activeUser'] = auth()->check() ? auth()->user() : null;
        $this->controllerData['activeMenu'] = $this->activeMenu;
        $this->controllerData['pageMeta'] = $this->pageMeta;
        $this->controllerData['breadCrumbs'] = $this->breadCrumbs ?? [];

        if ($this->viewPath) {
            $view = preg_replace(['/\.+/i', '/(^\.+)|(\.+$)/i'], ['.', ''], trim($this->viewPath)) . ".{$view}";
        }

        return view($view, $this->controllerData);
    }

    /**
     * Set Default Value for Request Input.
     *
     * @param string|array $name
     * @param mixed        $value
     *
     * @return void
     * @throws \Exception
     */
    protected function setDefault(string|array $name, mixed $value = null): void
    {
        if (! request()->input()) {
            setDefaultRequest($name, $value);
        }
    }

    /**
     * Set controller data.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     * @throws \Exception
     */
    protected function setData(string $name, $value): self
    {
        if (in_array($name, $this->reservedVariables)) {
            throw new Exception("Variable [{$name}] is reserved by this controller");
        }
        $this->controllerData[$name] = $value;

        return $this;
    }

    /**
     * Set page meta.
     *
     * @param string $metaKey
     * @param mixed  $metaValue
     *
     * @return $this
     */
    protected function setPageMeta(string $metaKey, $metaValue): self
    {
        $this->pageMeta[$metaKey] = $metaValue;

        return $this;
    }

    /**
     * Set Page title.
     *
     * @param string $title
     *
     * @return $this
     */
    protected function setPageTitle(string $title): self
    {
        $this->controllerData['pageTitle'] = $title;

        return $this;
    }

    /**
     * Set Back Link.
     *
     * @param string $link
     *
     * @return $this
     */
    protected function setBackLink(string $link): self
    {
        $this->controllerData['backLink'] = $link;

        return $this;
    }

    /**
     * Set BreadCrumb.
     *
     * @param  string|array  $breadcrumb
     * @return void
     */
    protected function setBreadCrumb(string|array $breadcrumb): void
    {
        $bc = collect();
        if (is_string($breadcrumb)) {
            $bc->add($this->breadCrumbFormat(['title' => $breadcrumb, 'url' => '#']));
        } else {
            foreach ($breadcrumb as $v) {
                if (is_string($v)) {
                    $bc->add($this->breadCrumbFormat($breadcrumb));
                    break;
                }
                $bc->add($this->breadCrumbFormat($v));
            }
        }

        $this->breadCrumbs = $bc;
    }

    /**
     * Add BreadCrumb.
     *
     * @param  string|array  $breadcrumb
     * @return void
     */
    protected function addBreadCrumb(string|array $breadcrumb): void
    {
        if (is_string($breadcrumb)) {
            $this->breadCrumbs->add($this->breadCrumbFormat(['title' => $breadcrumb, 'url' => '#']));
        } else {
            foreach ($breadcrumb as $v) {
                if (is_string($v)) {
                    $this->breadCrumbs->add($this->breadCrumbFormat($breadcrumb));
                    break;
                }
                $this->breadCrumbs->add($this->breadCrumbFormat($v));
            }
        }
    }

    /**
     * Breadcrumb formatter.
     *
     * @param  array  $breadcrumb
     * @return object
     */
    #[Pure]
    private function breadCrumbFormat(array $breadcrumb): object
    {
        $def = ['title' => '', 'url' => '#'];

        return (object) array_merge($def, Arr::only($breadcrumb, ['title', 'url']));
    }

    /**
     * @param string $parameter
     * @param $value
     * @return $this
     */
    public function addGlobalParams(string $parameter, $value): static
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
}
