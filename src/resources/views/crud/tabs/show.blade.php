@extends('crud::show')

@section('content')
    @include('different-core::crud.tabs.tabs', [ 'type' => 'show' ])
    @parent
@endsection
