@extends('crud::edit')

@section('header')
    @parent
    <div class="container-fluid animated fadeIn">
        @include('different-core::crudtab.tabs')
    </div>
@endsection

@section('content')
    <style>
        .crudTab .card {
            border-top: 0 !important;
            border-top-right-radius: 0;
            border-top-left-radius: 0;
        }
    </style>
    <div class="crudTab">
        @parent
    </div>
@endsection
