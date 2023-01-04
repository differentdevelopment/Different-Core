@extends('different-core::layouts.auth')

@section('content')
    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
        {!! csrf_field() !!}

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
