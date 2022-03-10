@extends('emails.layout')

@section('content')
    <p> {{ $invited_by }} invited you to join chatter. </p>
    <p> Click the button below to accept the invitation </p>

    <p><a href="{{ $url }}">
            <button class="btn btn-primary"> Accept invitation</button>
        </a></p>

    <p> or copy and paste the URL into your browser </p>

    <p><a href="{{ $url }}"> {{ $url }} </a></p> <br/>

    Thanks.

@endsection



