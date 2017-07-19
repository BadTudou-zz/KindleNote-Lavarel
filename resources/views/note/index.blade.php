@extends('layouts.app')

@section('content')
    <div class="container col-lg-12">
        <div class="col-lg-offset-8">
            <button type="button" class="btn btn-link"><a href="{{ action('NoteController@create')}}"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加</a></button>
            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;多选</button>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>&nbsp;排序</button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>

            @if (session()->has('isDownloadMarkdown'))
                <button class="btn btn-primary btn-sm" style="background-color: #FFF;">
                    <a href="{{action('ClippingController@download')}}" style="color:#0275d8; ">
                        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download
                    </a>
                    {{--onclick="event.preventDefault();--}}
                    {{--document.location.reload();"--}}
                </button>
            @endif
        </div>
        <div class="col-lg-12">
            @forelse ($notes as $note)
                <div class="card col-lg-4">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href={{action('NoteController@show', ['id' => $note->id])}}>{{$note->title}}</a>
                        </h4>
                        <h6 class="card-subtitle"> {{$note->author}}</h6>
                        <h6 class="card-subtitle text-muted"> {{$note->dateTime}}</h6>
                    </div>
                    <div class="card-block">
                        <p class="card-text">{{str_limit($note->text, 100)}}</p>
                        <a href="{{action('NoteController@edit', ['id' => $note->id])}}" class="card-link" > <i class="fa fa-1x fa-pencil" aria-hidden="true"></i>&nbsp;编辑</a>
                        <form action="{{action('NoteController@destroy',['id' => $note->id])}}" method="post" style="display: inline;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-link"><i class="fa fa-1x fa-trash" aria-hidden="true"></i>&nbsp;删除</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No notes</p>
            @endforelse
        </div>
    </div>
@endsection