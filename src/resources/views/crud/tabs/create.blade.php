@extends('crud::create')

@section('content')
    @include('different-core::crud.tabs.tabs', [ 'type' => 'create' ])
    @parent
@endsection
