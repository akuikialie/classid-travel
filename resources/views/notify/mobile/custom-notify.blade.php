@if (Session::has('notify.config'))
    @php
        $sessionNotify = json_decode(session()->get('notify.config'), true);

        switch ($sessionNotify['icon']) {
            case 'success':
                $color = 'green';
                break;

            case 'error':
                $color = 'red';

                break;

            default:
                $color = 'blue';
                break;
        }
    @endphp


    <div id="notify" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="305"
        data-menu-effect="menu-over">
        <h1 class="text-center mt-4">
            @switch($sessionNotify['icon'])
                @case('success')
                    <i class="fa fa-3x fa-check-circle color-green-dark"></i>
                @break

                @case('error')
                    <i class="fa fa-3x fa-times color-red-dark"></i>
                @break

                @default
                    <i class="fa fa-3x fa-question color-blue-dark"></i>
            @endswitch
        </h1>
        <h1 class="text-center mt-3 text-uppercase font-700">{{ $sessionNotify['title'] }}</h1>
        <p class="boxed-text-l">
            {{ \Illuminate\Support\Str::limit($sessionNotify['text'], 100) }}
        </p>
        <a href="#"
            class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-{{ $color }}-light">OK</a>
    </div>
    <script>
        notify('notify', 'show', 250);

        //Extending Menu Functions
        function notify(menuName, menuFunction, menuTimeout) {
            setTimeout(function() {
                if (menuFunction === "show") {
                    return document.getElementById(menuName).classList.add('menu-active'),
                        document.querySelectorAll('.menu-hider')[0].classList.add('menu-active')
                } else {
                    return document.getElementById(menuName).classList.remove('menu-active'),
                        document.querySelectorAll('.menu-hider')[0].classList.remove('menu-active')
                }
            }, menuTimeout)
        }
    </script>
@endif
