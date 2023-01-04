@extends('different-core::layouts.auth')

@section('content')
    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
        {!! csrf_field() !!}

        <div class="form-group">
            <label class="control-label" for="{{ $username }}">{{ trans('different-core::users.lang') }}</label>
            <div>
                @php
                    $locales_array = config('backpack.crud.locales');
                @endphp

                @if(!empty($locales_array) && count($locales_array) > 1)
                    <div class="nav-item d-md-down-none mr-2">
                        <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            @php
                                $locale = \Illuminate\Support\Facades\App::getLocale();
                                if($locale == 'en')
                                    $locale = 'gb';
                                if(strpos($locale, '_') !== false)
                                    $locale = substr($locale, 0, strpos($locale, '_'));
                            @endphp
                            <img
                                src="https://flagcdn.com/16x12/{{$locale}}.png"
                                width="16"
                                height="12"
                            >
                        </button>
                        <div class="dropdown-menu dropdown-menu-right py-0 lang-selector">
                            @foreach ($locales_array as $locale_shortcode => $locale_name)
                                <a
                                    class="dropdown-item {{ \Illuminate\Support\Facades\App::getLocale() == $locale_shortcode ? 'active disabled' : '' }}"
                                    href="{{ route('change-lang', [$locale_shortcode]) }}"
                                >
                                    @php
                                        if($locale_shortcode == 'en')
                                            $locale_shortcode = 'gb';
                                        if(strpos($locale_shortcode, '_') !== false)
                                            $locale_shortcode = substr($locale_shortcode, 0, strpos($locale_shortcode, '_'));
                                    @endphp
                                    <img
                                        src="https://flagcdn.com/16x12/{{$locale_shortcode}}.png"
                                        width="16"
                                        height="12"
                                    >
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="{{ $username }}">{{ trans('backpack::base.email_address') }}</label>

            <div>
                <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}">

                @if ($errors->has($username))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first($username) }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>

            <div>
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password">

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div>
                <button type="submit" class="btn btn-block btn-primary btn-lg">
                    {{ trans('backpack::base.login') }}
                </button>
            </div>
            @if(config('different-core.config.magic_link_login'))
                <div class="text-center my-2 font-weight-bold text-muted">
                    VAGY
                </div>
                <a href="{{ route('magic-link.get') }}" class="btn btn-block btn-outline-primary" >
                    {{  __('different-core::magic-link.login') }}
                </a>
            @endif
        </div>
    </form>
    @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
        <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
    @endif
    @if (config('backpack.base.registration_open'))
        <div class="text-center"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
    @endif
@endsection
