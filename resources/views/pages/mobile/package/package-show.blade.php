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
        'backDestination' => route('package.index'),
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
           class="btn btn-center-l btn-l bg-highlight font-700 text-uppercase under-slider-btn rounded-sm mt-n4 me-2 ms-4">Pilih Paket</a>
        <button
           class="btn btn-center-l btn-l btn-border color-highlight border-highlight font-700 text-uppercase under-slider-btn rounded-sm mt-n4 me-4 ms-2">Cek Estimasi</button>
      </div>
    </form>



    <div class="card card-style mt-4">
        <div class="content">

            <h4>Fasilitas</h4>
            <p>
                Fasilitas yang akan kamu terima ketika memilih paket ini!.
            </p>
            @forelse ($package->myFacilities as $facility)
                @php
                    $mediaFacility = $facility->getMedia('photo_collections');
                    $mediaFacilityUrl = null;
                    if ($mediaFacility->count() > 0) {
                        $mediaFacilityUrl = $mediaFacility[0]->getUrl();
                    }
                @endphp
                <a href="#" class="d-flex pb-4">
                    <div class="align-self-center">
                        <img src="{{ $mediaFacilityUrl }}" class="rounded-sm" width="60">
                    </div>
                    <div class="align-self-center ps-3">
                        <h5 class="mb-n1">{{ $facility->name }}</h5>
                    </div>
                    {{-- <div class="align-self-center ms-auto">
                        <i class="fa fa-play-circle font-24 color-highlight"></i>
                    </div> --}}
                </a>

            @empty
                <a href="#" class="d-flex pb-4">
                    <div class="align-self-center ps-3">
                        <h5 class="mb-n1">Tidak ada Fasilitas!</h5>
                    </div>
                </a>
            @endforelse


            <div class="divider mt-3 mb-3"></div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content">

            <h4>Destinasi</h4>
            <p>
                Destinasi yang akan kamu kunjungi ketika memilih paket ini!.
            </p>

            @forelse ($package->myDestinations as $destination)
                @php
                    $mediaDestination = $destination->getMedia('photo_collections');
                    $mediaDestinationUrl = null;
                    if ($mediaDestination->count() > 0) {
                        $mediaDestinationUrl = $mediaDestination[0]->getUrl();
                    }
                @endphp
                <a href="#" class="d-flex pb-4">
                    <div class="align-self-center">
                        <img src="{{ $mediaDestinationUrl }}" class="rounded-sm" width="60">
                    </div>
                    <div class="align-self-center ps-3">
                        <h5 class="mb-n1">{{ $destination->name }}</h5>
                    </div>
                    {{-- <div class="align-self-center ms-auto">
                        <i class="fa fa-play-circle font-24 color-highlight"></i>
                    </div> --}}
                </a>

            @empty
                <a href="#" class="d-flex pb-4">
                    <div class="align-self-center ps-3">
                        <h5 class="mb-n1">Tidak ada Fasilitas!</h5>
                    </div>
                </a>
            @endforelse
            <div class="divider mt-3 mb-3"></div>
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

            <div class="me-3 ms-3 mt-3 pt-1">
                <h2 class="font-700 mb-0">Data Kebreangkatan</h2>
                <p class="font-11 mb-3">
                    Silahakan mengisi data keberangkatan!.
                </p>
                <div class="input-style input-style-always-active has-borders no-icon my-4">
                    <label for="departure_city_id" class="color-highlight">Pilih Tempat Keberangkatan</label>
                    <select id="departure_city_id" name="departure_city_id">
                        @forelse ($cities as $city)
                            <option value="{{ $city->id }}">
                                {{ $city->name }}</option>
                        @empty
                            <option value="">Tidak ada tempat keberangkatan</option>
                        @endforelse
                    </select>
                    <span><i class="fa fa-chevron-down"></i></span>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <i class="fa fa-check disabled invalid color-red-dark"></i>
                    <em></em>
                </div>
                <div class="input-style input-style-always-active has-borders no-icon my-4">
                    <label for="schedule_id" class="color-highlight">Pilih Tanggal Keberangkatan</label>
                    <select id="schedule_id" name="schedule_id">
                        @forelse ($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ \Carbon\Carbon::parse($schedule->departure_date)->format('d F Y') }}</option>
                        @empty
                            <option value="">Tidak ada jadwal keberangkatan</option>
                        @endforelse
                    </select>
                    <span><i class="fa fa-chevron-down"></i></span>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <i class="fa fa-check disabled invalid color-red-dark"></i>
                    <em></em>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="#"
                        class="close-menu btn btn-full btn-m btn-border rounded-s text-uppercase font-900 color-dark-light border-dark-dark me-2 ">Batal</a>
                    <button
                        class="close-menu btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 ">Konfirmasi</button>
                </div>
            </div>
        </form>
    </div>
@endsection
