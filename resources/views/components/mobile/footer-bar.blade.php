<div id="footer-bar" class="footer-bar-5 bg-highlight">
    <a href="{{ route('home.index') }}" class="{{ url()->current() == route('home.index') ? 'active-nav' : '' }}"><i class="fas fa-home text-white"></i></a> {{-- Home --}}
    <a href="{{ route('jamaah.index') }}" class="{{ url()->current() == route('jamaah.index') ? 'active-nav' : '' }}"><i class="far fa-id-badge text-white"></i></a> {{-- Daftarkan jamaah --}}
    <a href="{{ route('package.index') }}" class="{{ url()->current() == route('package.index') ? 'active-nav' : '' }}"><i class="fas fa-cubes text-white"></i></a> {{-- menu daftar paket --}}
    <a href="{{ route('tabungan.index') }}" class="{{ url()->current() == route('tabungan.index') ? 'active-nav' : '' }}"><i class="fas fa-money-check text-white"></i></a> {{-- Tabungan --}}
    <a href="{{ route('profile.index') }}"  class="{{ url()->current() == route('profile.index') ? 'active-nav' : '' }}"><i class="fas fa-user-circle text-white"></i></a> {{-- Profile --}}
</div>
