@yield('topbar_right')

@if (session('account_id') && (backpack_user()->selectable_accounts->count() > 0))
    <li class="nav-item mr-4">
        <form action="{{ route('admin.change-account') }}" class="" method="POST" id="accountSelectorForm">
            @csrf
            <select class="form-select" id="accountSelector" name="account_id">
                @foreach (backpack_user()->selectable_accounts as $account)
                    <option
                        value="{{ $account->id }}"
                        {{ session('account_id') == $account->id ? 'selected' : ''}}
                    >
                        {{ $account->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit">Váltás</button>
        </form>
    </li>
@endif
