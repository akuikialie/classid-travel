@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Paket</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div>

    @forelse ($packages as $package)
        <div class="card card-style">
            <div class="content mt-3">
                <div class="d-flex">
                    <div class="align-self-center mt-1 ps-4 me-2">
                        <h4 class="color-theme font-600">{{ $package->name }}</h4>
                        <p class="mt-n2 font-11 color-highlight">
                            {{ $package->description }}
                        </p>
                    </div>
                    <div class="ms-auto align-self-center me-3">
                        <span
                            class="badge bg-red-dark color-white font-11 font-500 py-1 px-2">{{ rupiahFormat($package->amount) }}</span>
                    </div>
                </div>
                <div class="divider mt-3 mb-4"></div>
                <div class="row mb-0 mt-3">
                    <div class="col pe-0">
                        <h5 class="mb-0">Fasilitas - <span
                                class="badge bg-highlight color-white">{{ $package->my_facilities_count }}</span></h5>
                        @if ($package->my_facilities_count > 0)
                            <ul >
                                @foreach ($package->myFacilities as $facility)
                                    <li></i>{{ $facility->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="col ps-0">
                        <h5 class="mb-0">Destinasi - <span
                                class="badge bg-highlight color-white">{{ $package->my_destinations_count }}</span></h5>
                        @if ($package->my_destinations_count > 0)
                            <ul >
                                @foreach ($package->myDestinations as $destination)
                                    <li></i>{{ $destination->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="divider divider-margins mt-2"></div>
                <div class="mt-0">
                    @php
                        $mediaItems = $package->getMedia('thumbnail');
                        $mediaUrl = null;
                        if ($mediaItems->count() > 0) {
                            $mediaUrl = $mediaItems[0]->getUrl();
                        }
                    @endphp
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <img src="{{ $mediaUrl }}" class="img-fluid rounded-sm shadow-xl">
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <a href="{{ route('package.show', $package->id) }}"
                                class="btn btn-full btn-m bg-highlight text-uppercase font-700 rounded-sm">Pilih
                                Paket</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse

@endsection
