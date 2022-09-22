@extends('crud::list')

@section('content')
    @include('different-core::crud.tabs.tabs', [ 'type' => 'list' ])
    @parent
@endsection
