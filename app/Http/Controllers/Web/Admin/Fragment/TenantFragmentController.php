<?php

namespace App\Http\Controllers\Web\Admin\Fragment;

use App\Http\Controllers\Controller;
use App\Traits\ViewSupport;
use Illuminate\Http\Request;

class TenantFragmentController extends Controller
{
    public function overview()
    {
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-overview');
    }

    public function metadata()
    {

    }

    public function media($params)
    {
        $tenant = collect(array_column($params, 'tenant'))->first();

        $mediaCollections = collect($tenant->media)->groupBy('collection_name');
        $this->addGlobalParams('media_collections', $mediaCollections ?? []);

        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media');
    }


    public function media_edit($params)
    {
        $tenant = collect(array_column($params, 'tenant'))->first();
        $parameter = collect(array_column($params, 'parameter'))->first();
        $mediaItems = $tenant->getMedia($parameter);
        $mediaCollections = [];
        foreach ($mediaItems as $item){
            $mediaCollections[] = [
                'image_url' => $item->getUrl(),
                'order' => $item->getCustomProperty('order'),
            ];
        }
        $this->addGlobalParams('media_items', $mediaCollections);
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media-edit');
    }

    /**
     * @param $params
     * @return void
     */
    public function setting($params)
    {
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-setting');
    }
    
}
