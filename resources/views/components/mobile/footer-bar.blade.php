<div id="footer-bar" class="footer-bar-5">
    <a href="{{ route('home.index') }}" class="{{ url()->current() == route('home.index') ? 'active-nav' : '' }}"><i class="fas fa-home"></i></a> {{-- Home --}}
    <a href="{{ route('jamaah.index') }}" class="{{ url()->current() == route('jamaah.index') ? 'active-nav' : '' }}"><i class="far fa-id-badge"></i></a> {{-- Daftarkan jamaah --}}
    <a href="{{ route('package.index') }}" class="{{ url()->current() == route('package.index') ? 'active-nav' : '' }}"><i class="fas fa-cubes"></i></a> {{-- menu daftar paket --}}
    <a href="{{ route('tabungan.index') }}" class="{{ url()->current() == route('tabungan.index') ? 'active-nav' : '' }}"><i class="fas fa-money-check"></i></a> {{-- Tabungan --}}
    <a href="{{ route('profile.index') }}"  class="{{ url()->current() == route('profile.index') ? 'active-nav' : '' }}"><i class="fas fa-user-circle"></i></a> {{-- Profile --}}
</div>
