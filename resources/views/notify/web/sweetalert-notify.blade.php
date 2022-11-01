<script src="{{ asset('web/vendor/sweetalert/sweetalert2.js') }}"></script>
@if (Session::has('notify.config'))
  <script>
    Swal.fire({!! Session::pull('notify.config') !!});
  </script>
@endif

@if($errors->any())
  @php
    $listError = '';
      foreach ($errors->all() as $error){
          $listError .= "<li>{$error}</li>";
      }
  @endphp
  <script>
    Swal.fire({
      title: '<strong>Informasi Validasi</strong>',
      icon: 'info',
      html:
        '<div class="alert alert-info">' +
          '<ul>' +
            '{!! $listError !!}'+
          '</ul>' +
        '</div>',
      focusConfirm: false,

    })
  </script>
@endif
