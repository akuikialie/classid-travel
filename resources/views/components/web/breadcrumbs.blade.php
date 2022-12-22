<div class="d-flex flex-column align-items-start justify-content-center flex-wrap">
  <!--begin::Heading-->
  <h1 class="text-dark fw-bol d my-0 fs-2">{{ $pageTitle ?? '' }}</h1>
  <!--end::Heading-->
  <!--begin::Breadcrumb-->
  <ul class="breadcrumb fw-semibold fs-base my-1">
    <li class="breadcrumb-item text-muted">
      <a href="{{ route('admin.dashboard') }}" class="text-muted">Home</a>
    </li>
    @foreach ($breadCrumbs ?? [] as $bc)
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
