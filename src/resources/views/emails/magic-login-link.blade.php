@extends('different-core::email.template')

@section('style')
    <style type="text/css">
        a {
            color: #fff;
        }

        a.footer-link {
            color: #2e58ff;
        }

        /*
        .background {
            background: #c7c7c7;
        }

        .foreground {
            background: #526c8b;
        }
        */
    </style>
@endsection

@section('content')
<x-different-core::email-title>
    Üdv, {{ $name }}!
</x-different-core::email-title>
<x-different-core::email-content>
    <p>
        A bejelentkezéshez kérjük kattintson az alábbi linkre.
    </p>
</x-different-core::email-content>
<x-different-core::email-button
    :href="$url"
    text="Bejelentkezés"
    background="#2e58ff"
    textColor="#fff"
></x-different-core::email-button>
<x-different-core::email-content>
    <p>
        <a href="{{ $url }}">{{ $url }}</a>
    </p>
</x-different-core::email-content>
@endsection
