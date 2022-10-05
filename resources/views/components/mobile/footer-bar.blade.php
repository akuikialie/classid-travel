<div id="footer-bar" class="footer-bar-5">
    <a href="{{ route('home.index') }}" class="{{ url()->current() == route('home.index') ? 'active-nav' : '' }}"><i class="fas fa-home"></i></a> {{-- Home --}}
    <a href="index-components.html"><i class="far fa-id-badge"></i></a> {{-- Daftarkan jamaah --}}
    <a href="index-media.html"><i data-feather="image" data-feather-line="1" data-feather-size="21"
            data-feather-color="green-dark" data-feather-bg="green-fade-light"></i><span>Media</span></a>
    <a href="{{ route('tabungan.index') }}" class="{{ url()->current() == route('tabungan.index') ? 'active-nav' : '' }}"><i class="fas fa-money-check"></i></a> {{-- Tabungan --}}
    <a href="index-settings.html"><i class="fas fa-user-circle"></i></a> {{-- Profile --}}
</div>
