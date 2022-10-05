<!-- mulai menabung steps-1-->
<div id="mulai-menabung" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="350"
    data-menu-effect="menu-over">

    {{-- <div class="card header-card shape-rounded" data-card-height="200">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div> --}}

    <div class="mt-3 pt-1 pb-1">
        <h1 class="text-center">
            <i data-feather="moon" data-feather-line="1" data-feather-size="60" data-feather-color="gray-dark"
                data-feather-bg="none"></i>
        </h1>
        <h1 class="text-center color-black font-22 font-700">Pilih Jenis Tabungan</h1>
        <p class="text-center mt-n2 mb-3 font-11 color-black">Dana akan otomatis terisi ke tabungan yang terpilih</p>
    </div>
    <div class="splide single-slider slider-has-arrows slider-no-dots pb-2" id="single-slider-1">
        <div class="splide__track">
            <div class="splide__list">

                <div class="splide__slide px-3">
                    @include('pages.mobile.tabungan.menu.menu-tabungan', [
                        'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                        'savings' => '900.000',
                    ])
                </div>

                <div class="splide__slide px-3">
                    @include('pages.mobile.tabungan.menu.menu-tabungan', [
                        'namaTabungan' => 'Tabungan Umrah 2024',
                        'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                        'savings' => '12.500.000',
                        'targetSavings' => '32.000.000',
                    ])
                </div>

                <div class="splide__slide px-3">
                    @include('pages.mobile.tabungan.menu.menu-tabungan', [
                        'namaTabungan' => 'Tabungan Wisata',
                        'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                        'savings' => '890.000',
                        'targetSavings' => '2.000.000',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
