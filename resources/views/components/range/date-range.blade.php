<div class="fv-row mb-8">
    <!--begin::Email-->
    <label class="required" for="date_from">Date From</label>
    <input class="form-control date-range-picker" name="date_from" placeholder="Pick date rage"
           value="{{now()->startOfMonth()->toDateString()}}" id="date_from"/>

    @error('date_from')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="fv-row mb-8">
    <!--begin::Email-->
    <label class="required" for="date_to">Date To</label>
    <input class="form-control date-range-picker" name="date_to" placeholder="Pick date rage"
           value="{{now()->toDateString()}}" id="date_to"/>

    @error('date_to')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

@push('js')
    <script>
        $(".date-range-picker").flatpickr();

    </script>
@endpush
