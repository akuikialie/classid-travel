<!--begin::Footer container-->
<div class="app-container container-fluid d-flexflex-center flex-md-stack py-3">
  <!--begin::Copyright-->
  <div class="text-right text-muted order-2 order-md-1">
    Copyright © {{ date("Y") }}
    {{-- <strong>{{ !empty(school('alias')) ? school('alias') : '' }}</strong>, --}}
    MSS <strong>V1.0-alpha</strong>.
    {{ in_array(app()->environment(), ['prod', 'production'])?'':'@dev' }}
    <strong><a target="_blank" href="http://class.id">class.id</a></strong>
    <small>- {{ getHostName() }}</small>
  </div>
  <!--end::Copyright-->
</div>
<!--end::Footer container-->
