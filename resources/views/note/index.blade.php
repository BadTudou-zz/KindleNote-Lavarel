@extends('layouts.app')

@section('content')
    <div class="container col-lg-12">

        <!--toolbar -->
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
                        <i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;Download
                    </a>
                </button>
            @endif
        </div>

        <!-- notes list -->
        <div class="col-lg-offset-1 col-lg-12">
            @forelse ($notes as $note)
                <div class="card col-lg-4" style="display: block;">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href={{action('NoteController@show', ['id' => $note->id])}}>{{str_limit($note->title, 12)}}</a>
                        </h4>
                        <h6 class="card-subtitle"> {{str_limit($note->author, 36)}}</h6>
                        <h6 class="card-subtitle text-muted" style="margin-top: 5px;"> {{$note->dateTime}}</h6>
                    </div>
                    <!-- operation of the notes -->
                    <div class="card-block" style="margin: 0px; padding: 0px">
                        <p class="card-text" style="height: 70px;">{{str_limit($note->text,120)}}</p>
                        <a href="{{action('NoteController@edit', ['id' => $note->id])}}" class="card-link" > <i class="fa fa-1x fa-pencil" aria-hidden="true"></i>&nbsp;编辑</a>

                        <form action="{{action('NoteController@markdown',['id' => $note->id])}}" method="GET" style="display: inline;">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-link"><i class="fa fa-1x fa-download" aria-hidden="true"></i>&nbsp;下载</button>
                        </form>

                        <form action="{{action('NoteController@destroy',['id' => $note->id])}}" method="post" style="margin-left:10px; display: inline;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-1x fa-trash" aria-hidden="true"></i>&nbsp;删除</button>
                        </form>

                    </div>
                </div>
            @empty
                <p>No notes</p>
            @endforelse
        </div>

        <!-- paging -->
        <div class="col-lg-8 col-lg-offset-6" style="float: none">
            <div class="col-lg-6">
                <nav aria-label="...">
                    <ul class="pagination" style="margin: 0px;">
                        @if($pagination->current == 1)
                            <li class="page-item disabled">
                        @else
                            <li class="page-item">
                        @endif
                            <a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->previous])}}" tabindex="-1">Previous</a>
                            </li>
                        @if($pagination->current != 1)
                            <li class="page-item"><a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->previous])}}">{{$pagination->previous}}</a></li>
                        @endif
                        <li class="page-item active">
                            <a class="page-link" href="#">{{$pagination->current}}<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="page-item"><a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->next])}}">{{$pagination->next}}</a></li>
                        <li class="page-item">
                            <a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->next])}}">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="input-group col-lg-4" style="margin-top: 2px; padding-left: 0px;">
                <form id="page-form" action="{{action('NoteController@index')}}" method="GET" style="margin:0px;display:inline;">
                    {{ csrf_field() }}
                    <div class="col-lg-5">
                        <input type="text" class="form-control" id="page" name="page" placeholder="Page">
                    </div>
                    <span class="col-lg-4 " style="margin: 0px;">
                        <button type="submit" class="btn btn-primary" onclick="
                            event.preventDefault();
                            document.getElementById('page-form').submit();">Go
                        </button>
                    </span>
                    <span class="col-lg-3 text-center">{{$pagination->total}}</b><br><small>Pages</small></span>
                </form>

            </div>
        </div>
    </div>
@endsection