<script src="{{ asset('web/vendor/sweetalert/sweetalert2.js') }}"></script>
@if (Session::has('notify.config'))
    <script>
        Swal.fire({!! Session::pull('notify.config') !!});
    </script>
@endif
