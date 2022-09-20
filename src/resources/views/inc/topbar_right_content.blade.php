@yield('topbar_right')

@if (session('account_id') && backpack_user()->selectable_accounts->count() > 1)
    <li class="nav-item d-md-down-none mr-2">
        <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            {{ backpack_user()->selectable_accounts->firstWhere('id', session('account_id'))->name }}
        </button>
        <div class="dropdown-menu dropdown-menu-right py-0">
            @foreach (backpack_user()->selectable_accounts as $account)
                <a 
                    class="dropdown-item {{ session('account_id') == $account->id ? 'active disabled' : '' }}"
                    href="{{ route('admin.change-account', [$account->id]) }}"
                >
                    {{ $account->name }}
                </a>
            @endforeach
        </div>
    </li>
@endif
