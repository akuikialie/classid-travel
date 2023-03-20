<!--begin::Row-->
<div class="row g-6 g-xl-9">

    @foreach (config('media-collections.default_collections') as $collection)
        @php
            $getMediaCollection = collect($media_collections);
            $getMediaCollection = $getMediaCollection[$collection['name']] ?? [];
        @endphp

        <!--begin::Col-->
        <div class="col-md-6 col-xxl-4">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                    <!--begin::Avatar-->
                    <div class="tns tns-default" style="direction: ltr">
                        <!--begin::Slider-->
                        <div data-tns="true" data-tns-loop="true" data-tns-swipe-angle="false" data-tns-speed="1500"
                            data-tns-autoplay="true" data-tns-autoplay-timeout="5000" data-tns-controls="true"
                            data-tns-nav="false" data-tns-items="1" data-tns-center="false" data-tns-dots="false"
                            data-tns-prev-button="#kt_team_slider_prev1" data-tns-next-button="#kt_team_slider_next1">

                            @foreach ($getMediaCollection ?? [] as $media)
                                <!--begin::Item-->
                                <div class="text-center px-5 py-5">
                                    <img src="{{ $media->getUrl() }}" class="card-rounded mw-100" />
                                </div>
                                <!--end::Item-->
                            @endforeach
                        </div>
                        <!--end::Slider-->

                        <!--begin::Slider button-->
                        <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev1">
                            <span class="svg-icon svg-icon-3x">
                                <i class="fa-solid fa-arrow-left"></i>
                            </span>
                        </button>
                        <!--end::Slider button-->

                        <!--begin::Slider button-->
                        <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next1">
                            <span class="svg-icon svg-icon-3x">
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </button>
                        <!--end::Slider button-->
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <a href="#"
                        class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">{{ $collection['name'] }}</a>
                    <!--end::Name-->
                    <!--begin::Position-->
                    <div class="fw-semibold text-gray-400 mb-6">{{ $collection['description'] }}</div>
                    <!--end::Position-->
                    <!--begin::Info-->
                    <div class="d-flex flex-center flex-wrap">
                        <!--begin::Stats-->
                        <div
                            class="border border-gray-300 border-dashed text-center rounded min-w-80px py-3 px-4 mx-2 mb-3">
                            <div class="fs-6 fw-bold text-gray-700">
                                {{ count($getMediaCollection) }}/{{ config("media-collections.collection_settings.{$collection['name']}.max") }}
                            </div>
                            <div class="fw-semibold text-gray-400">Total</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Stats-->
                        <div
                            class="border border-gray-300 border-dashed text-center rounded min-w-80px py-3 px-4 mx-2 mb-3">
                            <a href="{{ route('admin.tenant.showProfile', ['slug' => 'media_edit', 'folder' => 'banners']) }}"
                                type="button" class="btn btn-primary hover-elevate-up fragment"
                                data-fragment="media_edit" data-fragment-parameter="banners">Detail Media</a>
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

        </div>
        <!--end::Col-->
    @endforeach
</div>
<!--end::Row-->

<br>
<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title fs-3 fw-bold">Travel Media</div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" action="{{ route('admin.tenant.auth_banner') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Collection Name</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-9 fv-row">
                    <input type="text" readonly="readonly" class="form-control form-control-solid" name="collection"
                        value="admin.travel_media.auth_logo" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-5">
                    <div class="col-12 row mb-2">
                        <!--begin::Col-->
                        <div class="col-xl-3">
                            <div class="fs-6 fw-semibold mt-2 mb-3">Auth logo</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                style="background-image: url({{ $currentBanner ?? asset('logo/96w/logo-pict@96px.png') }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-lg-300px h-lg-175px bgi-position-center"
                                    style="background-size: 75%; background-image: url({{ $currentBanner ?? asset('web/media/misc/auth-bg.png') }})">
                                </div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="collections"
                                        accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="collection_remove" />
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Cancel-->
                                <!--begin::Remove-->
                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
        <!--begin::Card footer-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
            <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
        </div>
        <!--end::Card footer-->
    </form>
    <!--end:Form-->
</div>
<!--end::Card-->
