@extends('layouts.base')

@section('baseLayout')
  <div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="page d-flex flex-row flex-column-fluid">
      @includeIf('layouts.blocks.menus')

      <!--begin::Wrapper-->
      <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        @includeIf('layouts.blocks.header')
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid pb-0 py-0" id="kt_content">
          @includeIf('layouts.blocks.toolbar')
          <!--begin::Post-->
          <div class="post fs-base flex-column-fluid" id="kt_post">
            @yield('content', '')
          </div>
          <!--end::Post-->
        </div>
        <!--end::Content-->

        {{-- theme()->getView('layout/_footer') --}}
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Page-->
  </div>
  @stack('modal')
  <div class="form-group" style="margin-top: 3%; float:right; position: relative; z-index: 10;">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
      <a href="#scrool_top" class="btn btn-light-primary p-3" id="scrool_top"><i class="fa-solid fa-square-up fs-1"></i></a>
    </div>
  </div>
@endsection
