@extends('layouts.app')

@section('content')
    <div class="container col-lg-12">
        <h4 class="display-6 text-center">{{$note->title}}</h4>
        <div class="text-center">{{$note->author}}</div>
        <div class="col-lg-offset-1 col-lg-10">
            <p class="lead">{{$note->text}}</p>
        </div>
    </div>
@endsection