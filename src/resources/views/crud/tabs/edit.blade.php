@extends('crud::edit')

@section('content')
    @include('different-core::crud.tabs.tabs', [ 'type' => 'edit' ])
    @parent
@endsection
