<!--begin::Row-->
<div class="row g-6 g-xl-9">

  @foreach(config('media-collections.default_collections') as $collection)
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
            <div
              data-tns="true"
              data-tns-loop="true"
              data-tns-swipe-angle="false"
              data-tns-speed="1500"
              data-tns-autoplay="true"
              data-tns-autoplay-timeout="5000"
              data-tns-controls="true"
              data-tns-nav="false"
              data-tns-items="1"
              data-tns-center="false"
              data-tns-dots="false"
              data-tns-prev-button="#kt_team_slider_prev1"
              data-tns-next-button="#kt_team_slider_next1">

              @foreach($getMediaCollection ?? [] as $media)
                <!--begin::Item-->
                <div class="text-center px-5 py-5">
                  <img src="{{ $media->getUrl() }}" class="card-rounded mw-100"/>
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
          <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">{{ $collection['name'] }}</a>
          <!--end::Name-->
          <!--begin::Position-->
          <div class="fw-semibold text-gray-400 mb-6">{{ $collection['description'] }}</div>
          <!--end::Position-->
          <!--begin::Info-->
          <div class="d-flex flex-center flex-wrap">
            <!--begin::Stats-->
            <div class="border border-gray-300 border-dashed text-center rounded min-w-80px py-3 px-4 mx-2 mb-3">
              <div class="fs-6 fw-bold text-gray-700">{{ count($getMediaCollection) }}/{{ config("media-collections.collection_settings.{$collection['name']}.max") }}</div>
              <div class="fw-semibold text-gray-400">Total</div>
            </div>
            <!--end::Stats-->
            <!--begin::Stats-->
            <div class="border border-gray-300 border-dashed text-center rounded min-w-80px py-3 px-4 mx-2 mb-3">
              <a href="{{ route('admin.tenant.showProfile', ['slug'=>'media_edit','folder'=>'banners']) }}" type="button" class="btn btn-primary hover-elevate-up fragment"
                 data-fragment="media_edit"
                 data-fragment-parameter="banners">Detail Media</a>
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
