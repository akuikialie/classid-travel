<!--begin::Page title-->
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
  <!--begin::Title-->
  <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $pageTitle ?? '' }}</h1>
  <!--end::Title-->
  <!--begin::Breadcrumb-->
  <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">
      <a href="{{ route('admin.dashboard') }}" class="text-muted">Home</a>
    </li>
    <!--end::Item-->

    @foreach ($breadCrumbs ?? [] as $bc)
      <!--begin::Item-->
      <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
      </li>
      <!--end::Item-->

      @if($bc->url == '#' || empty($bc->url))
        <li class="breadcrumb-item text-dark active"><span>{{ $bc->title }}</span></li>
      @else
        <li class="breadcrumb-item text-muted">
          <a href="{{ $bc->url }}" class="text-muted"><span>{{ $bc->title }}</span></a>
        </li>
      @endif
    @endforeach

  </ul>
  <!--end::Breadcrumb-->
</div>
<!--end::Page title-->
