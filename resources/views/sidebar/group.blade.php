<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
        @isset($icon)
            <i class="nav-icon {{ $icon }}"></i>
        @endif
        {{ $title ?? "" }}
    </a>
    <ul class="nav-dropdown-items">
        @isset($items)
            @foreach ($items as $item)
                {{ $item->render() }}
            @endforeach
        @endisset
    </ul>
</li>
