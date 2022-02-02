@push('after_styles')
    <style>
        #tabs_row .nav {
            background: #fff;
            margin-bottom: 15px;
            border-radius: .25rem;
            overflow: hidden;
        }

        #tabs_row .nav.nav-pills .nav-link {
            border-radius: 0 !important;
        }
    </style>
@endpush

<div class="row" id="tabs_row">
    <div class="col-12">
        <ul class="nav nav-pills shadow-xs">
            @if(isset($different_core_tabs))
                @foreach ($different_core_tabs as $tab)
                    {{ $tab->render() }}
                @endforeach
            @endif
        </ul>
    </div>
</div>
