@extends('checkout::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('checkout.name') !!}
    </p>
@endsection
