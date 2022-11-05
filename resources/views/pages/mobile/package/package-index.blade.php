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

  @php
    $a = 0;
  @endphp
  @forelse ($packages as $package)
    @php
      $a++;
    @endphp
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
          <div class="card card-style">
            <div class="content mt-0 mb-0">
              <div class="list-group list-custom-small list-icon-0">
                <a data-bs-toggle="collapse" class="no-effect" href="#destination-{{$a}}">
                  <i class="fa-solid fa-location-dot text-muted"></i>
                  <span class="font-14">Tujuan
                    <span
                      class="badge bg-highlight color-white me-2">{{ $package->my_destinations_count }}</span>
                  </span>
                </a>
              </div>

              <div class="collapse" id="destination-{{$a}}">
                <div class="list-group list-custom-small ps-3">
                  @foreach ($package->myDestinations ?? [] as $destination)
                    <a href="#">
                      <i class="fa-solid fa-arrow-right"></i>
                      <span>{{ $destination->name }}</span>
                    </a>
                  @endforeach
                </div>
              </div>

              <div class="list-group list-custom-small list-icon-0">
                <a data-bs-toggle="collapse" class="no-effect" href="#facility-{{$a}}">
                  <i class="fa-solid fa-bars-progress text-muted"></i>
                  <span class="font-14">Fasilitas
                    <span
                      class="badge bg-highlight color-white me-2">{{ $package->my_facilities_count }}</span>
                  </span>
                </a>
              </div>
              <div class="collapse" id="facility-{{$a}}">
                <div class="list-group list-custom-small ps-3">
                  @foreach ($package->myFacilities as $facility)
                    <a href="#">
                      <i class="fa-solid fa-arrow-right"></i>
                      <span>{{ $facility->name }}</span>
                    </a>
                  @endforeach
                </div>
              </div>

              <div class="list-group list-custom-small list-icon-0">
                <a data-bs-toggle="collapse" class="no-effect" href="#itinerary-{{$a}}">
                  <i class="fa-solid fa-clipboard-list text-muted"></i>
                  <span class="font-14">Kegiatan
                    <span
                      class="badge bg-highlight color-white me-2">{{ $package->my_itineraries_count }}</span>
                  </span>
                  <i class="fa fa-angle-down"></i>
                </a>
              </div>
              <div class="collapse" id="itinerary-{{$a}}">
                @for($i = 0; $i < $package->my_itineraries_count; $i++)

                  <div class="list-group list-custom-small list-icon-0">
                    <a data-bs-toggle="collapse" class="no-effect" href="#activity-{{"{$a}-{$i}"}}">
                      <i class="fa font-14 fa-share-alt color-red-dark"></i>
                      <span class="font-14">{{$package->myItineraries[$i]->name}}
                        <span> <sup>Hari ke-{{$package->myItineraries[$i]->day}}</sup> </span>
                      </span>
                      <i class="fa fa-angle-down"></i>
                    </a>
                  </div>

                  <div class="collapse" id="activity-{{"{$a}-{$i}"}}">
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

            </div>
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
