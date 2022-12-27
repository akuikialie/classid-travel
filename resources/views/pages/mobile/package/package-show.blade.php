@php use Carbon\Carbon; @endphp
@extends('layouts.mobile.app-mobile')

@section('mobile-content')
  @php
    $mediaItems = $package->getMedia('thumbnail');
    $mediaUrl = null;
    if ($mediaItems->count() > 0) {
        $mediaUrl = $mediaItems[0]->getUrl();
    }
  @endphp
  @include('components.mobile.toolbar', [
      'title' => 'Detail Paket',
      'backDestination' => url()->previous(),
  ])

  <div class="card header-card shape-rounded" data-card-height="210">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img" data-src="{{ asset('mobile/images/pictures/20s.jpg') }}"></div>
  </div>

  <div class="card header-card shape-rounded" data-card-height="150">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img" data-src="{{ asset('mobile/images/pictures/20s.jpg') }}"></div>
  </div>

  <div class="card card-style mb-0">
    <div class="card mb-0 ">
      <div class="content pt-3">
        <img src="{{ $mediaUrl }}" class="img-fluid mx-auto" width="300">
        <h1 class="font-33 pt-5 text-center">{{ $package->name }}</h1>
        <p class="boxed-text-l pb-2">
          {{ $package->description }}
        </p>
      </div>
      {{-- <div class="card-bottom text-center">
          <h1 class="color-white font-32 font-700">{{ $package->name }}</h1>
          <p class="font-14 color-white px-4 pb-3 opacity-60">
              {{ $package->description }}
          </p>
      </div>
      <div class="card-overlay bg-gradient opacity-90"></div> --}}
    </div>
  </div>
  <form action="{{  route('perencanaan.check-estimasi') }}" method="get">
    <input hidden name="package" value="{{$package->id}}">
    <div class="d-flex justify-content-between">
      <a href="#" data-menu="confirm-action"
         class="btn btn-center-l btn-l bg-highlight font-700 text-uppercase under-slider-btn rounded-sm mt-n4 me-2 ms-4">Pilih
        Paket</a>
      <button
        class="btn btn-center-l btn-l btn-border color-highlight border-highlight font-700 text-uppercase under-slider-btn rounded-sm mt-n4 me-4 ms-2">
        Cek Estimasi
      </button>
    </div>
  </form>

  <div class="card card-style mt-4">
    <div class="content">
      <div class="list-group list-custom-small list-icon-0">
        <a data-bs-toggle="collapse" class="no-effect" href="#destination">
          <span
            class="badge bg-highlight color-white me-2">{{ $package->my_destinations_count }}</span>
          <h4>Destinasi</h4>
        </a>
      </div>
      <p>
        Destinasi yang akan kamu kunjungi ketika memilih paket ini!.
      </p>
      <div class="collapse" id="destination">
        <div class="list-group list-custom-small ps-3">
          @foreach ($package->myDestinations ?? [] as $destination)
            <a href="#">
              <i class="fa-solid fa-arrow-right"></i>
              <span>{{ $destination->name }}</span>
            </a>
          @endforeach
        </div>
      </div>
      <div class="divider mt-1 mb-1"></div>
    </div>
  </div>

  <div class="card card-style mt-4">
    <div class="content">

      <div class="list-group list-custom-small list-icon-0">
        <a data-bs-toggle="collapse" class="no-effect" href="#facility">
          <span
            class="badge bg-highlight color-white me-2">{{ $package->my_facilities_count }}</span>
          <h4>Fasilitas</h4>
        </a>
      </div>
      <p>
        Fasilitas yang akan kamu terima ketika memilih paket ini!.
      </p>
      <div class="collapse" id="facility">
        <div class="list-group list-custom-small ps-3">
          @foreach ($package->myFacilities as $facility)
            <a href="#">
              <i class="fa-solid fa-arrow-right"></i>
              <span>{{ $facility->name }}</span>
            </a>
          @endforeach
        </div>
      </div>
      <div class="divider mt-1 mb-1"></div>
    </div>
  </div>

  <div class="card card-style mt-4">
    <div class="content">

      <div class="list-group list-custom-small list-icon-0">
        <a data-bs-toggle="collapse" class="no-effect" href="#itinerary">
          <span
            class="badge bg-highlight color-white me-2">{{ $package->my_itineraries_count }}</span>
          <h4>Daftar Kegiatan </h4>
        </a>
      </div>
      <p>
        Daftar kegiatan untuk paket {{ $package->name }}
      </p>

      <div class="collapse" id="itinerary">
        @for($i = 0; $i < $package->my_itineraries_count; $i++)

          <div class="list-group list-custom-small list-icon-0">
            <a data-bs-toggle="collapse" class="no-effect" href="#activity-{{$i}}">
              <i class="fa font-14 fa-share-alt color-red-dark"></i>
              <span class="font-14">{{$package->myItineraries[$i]->name}}
                        <span> <sup>Hari ke-{{$package->myItineraries[$i]->day}}</sup> </span>
                      </span>
              <i class="fa fa-angle-down"></i>
            </a>
          </div>

          <div class="collapse" id="activity-{{$i}}">
            <div class="list-group list-custom-small ps-3">
              @foreach ($package->myItineraries[$i]->activities ?? [] as $_activity)
                <a href="#">
                  <i class="fa-solid fa-arrow-right"></i>
                  <span>{{ $_activity->activity }}</span>
                </a>
              @endforeach
            </div>
          </div>
        @endfor

      </div>
      <div class="divider mt-1 mb-1"></div>
    </div>
  </div>

@endsection

@section('external-mobile-content')
  <!---------------->
  <!---------------->
  <!--Menu Confirm-->
  <!---------------->
  <!---------------->
  <div id="confirm-action" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="360"
       data-menu-effect="menu-over">
    <form action="{{ route('package.add-to-jamaah', $package->id) }}" method="post">
      @csrf

      @include('pages.mobile.package.menu.menu-data-keberangkatan')
    </form>
  </div>
@endsection
