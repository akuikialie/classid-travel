<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title fs-3 fw-bold">Koleksi Media</div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" action="{{ route('admin.tenant.add-media') }}" method="post" enctype="multipart/form-data">
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
                    <input type="text" class="form-control form-control-solid"
                        @if (!config("media-collections.collection_settings.{$param}.is_editable_title")) readonly="readonly" @endif name="collection"
                        value="{{ $param }}" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-5">


                @for ($i = 0; $i < config("media-collections.collection_settings.{$param}.max"); $i++)
                    @php
                        $a = $i;
                        
                        $currentBanner = collect($media_items)
                            ->where('order', $a + 1)
                            ->last();
                        $currentBanner = $currentBanner['image_url'] ?? null;
                    @endphp
                    <div class="col-12 row mb-2">
                        <!--begin::Col-->
                        <div class="col-xl-3">
                            <div class="fs-6 fw-semibold mt-2 mb-3">Banner {{ $a + 1 }}</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                style="background-image: url({{ $currentBanner ?? asset('logo/96w/logo-pict@96px.png') }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-lg-300px h-lg-175px bgi-position-center"
                                    style="background-size: 75%; background-image: url({{ $currentBanner ?? asset('logo/96w/logo-pict@96px.png') }})">
                                </div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="collections[{{ $a + 1 }}]"
                                        accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="collection_remove[{{ $a + 1 }}]" />
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
                @endfor
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
