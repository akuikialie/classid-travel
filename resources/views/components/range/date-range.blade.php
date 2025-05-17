<div class="fv-row mb-8">
    <!--begin::Email-->
    <label class="required" for="date_from">Date From</label>
    <input class="form-control date-range-picker" name="date_from" placeholder="Pick date rage" type="date"
           value="{{ (isset($using_default_value) ? $using_default_value : false) ? now()->startOfMonth()->toDateString(): null}}" id="date_from"/>

    @error('date_from')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="fv-row mb-8">
    <!--begin::Email-->
    <label class="required" for="date_to">Date To</label>
    <input class="form-control date-range-picker" name="date_to" placeholder="Pick date rage" type="date"
           value="{{ (isset($using_default_value) ? $using_default_value : false) ?  now()->toDateString() : null}}" id="date_to"/>

    @error('date_to')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

@push('js')
    <script>
        flatpickr(".date-range-picker", {
            appendTo: document.body,
            clickOpens: true,
            allowInput: true,
            onOpen: function(selectedDates, dateStr, instance) {
                instance._input.setAttribute("data-flatpickr-open", "true");
            },
            onClose: function(selectedDates, dateStr, instance) {
                instance._input.removeAttribute("data-flatpickr-open");
            }
        });

        document.querySelectorAll(".date-range-picker").forEach(function(el) {
            el.addEventListener("mousedown", function(e) {
                e.stopPropagation();
            });
        });
    </script>
@endpush
