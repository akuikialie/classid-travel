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

        $this->addGlobalParams('auth_logo', $tenant->getFirstMedia('auth_logo')->getFullUrl());

        $mediaCollections = collect($tenant->media)->groupBy('collection_name');
        $this->addGlobalParams('media_collections', $mediaCollections ?? []);


        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media');
    }


    public function media_edit($params)
    {
        $tenant = collect(array_column($params, 'tenant'))->first();
        $folder = collect(array_column($params, 'folder'))->first();
        $mediaItems = $tenant->getMedia($folder);
        $mediaCollections = [];
        foreach ($mediaItems as $item){
            $mediaCollections[] = [
                'image_url' => $item->getUrl(),
                'order' => $item->getCustomProperty('order'),
            ];
        }
        $this->addGlobalParams('param', $folder);
        $this->addGlobalParams('media_items', $mediaCollections);
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-media-edit_banners');
    }

    /**
     * @param $params
     * @return void
     */
    public function setting($params)
    {
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-setting');
    }

    public function misc($params)
    {
        $this->addGlobalParams('fragment_view', 'pages.web.tenant.fragment.fragment-misc');
    }

}
