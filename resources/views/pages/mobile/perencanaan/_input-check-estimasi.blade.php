
<div class="input-style input-style-always-active has-borders no-icon my-4">
  <label for="pilih-package">Pilih paket anda</label>
  <select id="pilih-package" name="package" class="d-flex">
    @forelse ($planPackages as $package)
      <option value="{{ $package->id }}" {{ request()->get('package') == $package->id ? 'selected' : '' }}>{{ $package->name }} - {{ numberFormat($package->amount) }}</option>
    @empty
      <option value="" selected disabled>Paket belum tersedia</option>
    @endforelse
  </select>
  <span><i class="fa fa-chevron-down"></i></span>
  <i class="fa fa-check disabled valid color-green-dark"></i>
  <i class="fa fa-check disabled invalid color-red-dark"></i>
  <em></em>
</div>
