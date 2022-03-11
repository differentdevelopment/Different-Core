@if (!isset($permission) || user_can($permission))
    <li class="nav-item">
        <a 
            class="nav-link" 
            href="{{ $url }}"
        >
            @if ($icon !== "")
                <i class="nav-icon {{ $icon }}"></i>
            @endif
            {{ __($title) }}
        </a>
    </li>
@endif
