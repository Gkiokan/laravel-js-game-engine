@extends('errors.minimal')

@section('title', __(config('app.name') . ' Service Mode'))
@section('code', '503')
@section('message', __(config('app.name') . ' Service Mode'))

@section('sub')
    <p>
        We are updating our service. <br>
        We are soon back. <br>
        <br>
        Stay tuned! <br>
        <br>        
    </p>
@endsection
