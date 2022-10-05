<span class="indicator-label">
    {{ $slot ?? __('Submit') }}
</span>
<span class="indicator-progress">
    {{ $message ?? __('Please wait...') }}
    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
</span>
