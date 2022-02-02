<li class="nav-item">
    <a 
        class="{{ "nav-link" . ($active ? " active": "") . ($disabled ? " disabled" : "") }}"
        href="{{ $url }}"
    >
        {{ $title ?? "" }}
    </a>
</li>

                    {{--@if (!isset($tab['permission']) || user_can($tab['permission']))
                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs($tab['route']) ? ' active' : '' }} {{ isset($tab['only_with_entry']) && $tab['only_with_entry'] && !isset(${$tab['entry_name'] ?? 'entry'}) ? ' disabled ' : '' }}"
                                href="{{ isset(${$tab['entry_name'] ?? 'entry'}) ? route($tab['route'], ${$tab['entry_name'] ?? 'entry'}) : '#'}}"
                            >
                                {{ $tab['label'] }}
                            </a>
                        </li>
                    @endif--}}
