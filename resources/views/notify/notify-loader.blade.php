@php
    $prefix = request()
        ->route()
        ->getPrefix();

    $loadView = 'mobile';

    if (str_contains($prefix, 'admin')) {
        $loadView = 'web';
    }

@endphp
@include('notify.web.sweetalert-notify')
{{--@foreach (Config::get('notify.view')[$loadView] as $view)--}}
{{--    @include($view)--}}
{{--@endforeach--}}

