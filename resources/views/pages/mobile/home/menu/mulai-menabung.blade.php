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
  @foreach ($list_moneyboxs as $moneybox)
    @include('pages.mobile.tabungan.menu.menu-tabungan', $moneybox)
  @endforeach
</div>
