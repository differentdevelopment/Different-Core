@if (!isset($permission) || user_can($permission))
    <a 
        class="{{ "btn" . ($disabled ? " disabled" : "") }}"
        href="{{ $url }}"
    >
        @if ($icon !== "")
            <i class="{{ $icon }}"></i>
        @endif
        {{ $title ?? "" }}
    </a>
@endif
