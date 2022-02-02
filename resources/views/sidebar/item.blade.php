<li class="nav-item @active({{ $url }})">
    <a 
        class="nav-link" 
        href="{{ $url }}"
    >
        @if (isset($icon))
            <i class="nav-icon {{ $icon }}"></i>
        @endif
        {{ $title ?? "" }}
    </a>
</li>
