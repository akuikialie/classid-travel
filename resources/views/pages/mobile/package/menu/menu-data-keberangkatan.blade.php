<div class="me-3 ms-3 mt-3 pt-1">
  <h2 class="font-700 mb-0">Data Keberangkatan</h2>
  <p class="font-11 mb-3">
    Silahakan mengisi data keberangkatan!.
  </p>
  <div class="input-style input-style-always-active has-borders no-icon my-4">
    <label for="departure_city_id" class="color-highlight">Pilih Tempat Keberangkatan</label>
    <select id="departure_city_id" name="departure_city_id">
      @forelse ($cities as $city)
        <option value="{{ $city->id }}" {{ old('departure_city_id') == $city->id ? 'selected' : null }}>
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
        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : null }}>
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
      class="close-menu btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 ">Konfirmasi
    </button>
  </div>
</div>
