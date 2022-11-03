<?php

namespace App\Http\Controllers\Web\Admin\Fragment;

use App\Http\Controllers\Controller;
use App\Traits\ViewSupport;
use Illuminate\Http\Request;

class TenantFragmentController extends Controller
{
    use ViewSupport;
    public function overview()
    {

    }

    public function metadata()
    {

    }

    public function media($params)
    {
        $tenant = collect(array_column($params, 'tenant'))->first();

        $mediaCollections = collect($tenant->media)->groupBy('collection_name');
        $this->setGlobalParams('media_collections', $mediaCollections);

        $this->setGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media');
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
        $this->setGlobalParams('media_items', $mediaCollections);
        $this->setGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media-edit');
    }

    public function setting()
    {
        $this->setGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-setting');
    }
}
