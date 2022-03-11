@extends('crud::create')

@section('header')
    @include('different-core::crudtab.tabs')
    @parent
@endsection

@section('content')
    <div class="crudtab">
        @parent
    </div>
@endsection

