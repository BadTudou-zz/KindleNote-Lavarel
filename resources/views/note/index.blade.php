@extends('layouts.app')
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{URL::asset('js/note.index.js')}}"></script>
@section('content')
    <div class="container col-lg-12">
        <!--toolbar -->
        <!-- blade mode -->
        <div class="col-lg-offset-1 col-lg-3" id="div-batch">
            <form id="batchForm" action="{{action('NoteController@batch',['action'=>'download'])}}" method="POST" style="display: inline;">
                {{ csrf_field() }}
                <button type="submit"  class="btn btn-primary btn-sm" onclick="batch('download')"><i class="fa fa-1x fa-download" aria-hidden="true" ></i>&nbsp;下载</button>
            </form>

            
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;导出</button>
                <div class="dropdown-menu">
                    <form id="batchForm" action="{{action('NoteController@batch',['action'=>'export/evernote'])}}" method="POST" style="display: inline;">
                        {{ csrf_field() }}
                       {{--  <button type="submit"  class="btn btn-primary btn-sm" onclick="batch('download')"><i class="fa fa-1x fa-download" aria-hidden="true" ></i>&nbsp;下载</button> --}}
                       <button type="submit"  class="btn" onclick="batch('export/evernote')"> <img src="{{URL::asset('/images/evernote_logo.png')}}" style="width: 24px; height: 24px;">&nbsp;Evernote</button>
                    </form>
                        
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>

            <form id="batchForm" action="{{action('NoteController@batch',['action'=>'delete'])}}" method="post" style="margin-left:10px; display: inline;">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger btn-sm" onclick="batch('delete')"><i class="fa fa-1x fa-trash" aria-hidden="true"></i>&nbsp;删除</button>
            </form>

            已选择 <span id="span-chooseNoteNumber" style="color:#0275d8;">0</span> 个
        </div>

        <div class="col-lg-offset-8">
            <button type="button" class="btn btn-link"><a href="{{ action('NoteController@create')}}"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加</a></button>
            <button type="button" class="btn btn-primary btn-sm" onclick="
                $('.batch').removeAttr(':hidden');
                $('.batch').toggle();
                if(isCheckBoxShow){
                    $('.batch').hide();
                    $('#div-batch').hide();
                }else{
                    $('#div-batch').show();
                    $('.batch').show();
                }
                isCheckBoxShow = !isCheckBoxShow;"
            ><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;多选</button>
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
            @else
                <button class="btn btn-secondary btn-sm btn-link" style="background-color: #FFF;" disabled>
                    <span style="color:#373a3c; ">
                        <i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;<s>Download</s>
                    </span>
                    </button>
            @endif


        </div>
        <!-- search result -->
        @if(isset($searchInput))
            <div class="col-lg-offset-1 col-lg-2">
                <i class="fa fa-search" aria-hidden="true">:{{$searchInput}}</i>
                <span class="badge badge-primary" style="background-color: #006de0">{{$searchResultNumber}}</span>
            </div>
            <div class="col-lg-offset-5 col-lg-4">
            @else
            <div class="col-lg-offset-8 col-lg-4">
            @endif
            <form id="search-form" action="{{action('NoteController@search')}}" method="GET" style="margin:0px;display:inline;">
                {{ csrf_field() }}
                <div id="div-searchbox" class="input-group">
                    <input type="text" name="searchInput" class="form-control" placeholder="笔记标题">
                        <span class="input-group-btn">
                            <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true" onclick="
                                event.preventDefault();
                                document.getElementById('search-form').submit();"></i>搜索
                            </button>
                        </span>
                </div>
            </form>
        </div>

        <!-- notes list -->
        <div class="col-lg-offset-1 col-lg-12">
            @forelse ($notes as $note)
                <div class="card col-lg-4" style="display: block;">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="{{action('NoteController@show', ['id' => $note->id])}}" title="{{$note->title}}">{{str_limit(html_entity_decode($note->title), 18)}}</a>
                        </h4>
                        <h6 class="card-subtitle"> {{str_limit($note->author, 34)}}</h6>
                    </div>
                    <div class="card-block" style="margin: 0px; padding: 0px">
                        <p class="card-subtitle text-muted" style="margin-top: 5px;"><small>{{$note->dateTime}}</small></p>

                        <p class="card-text" style="height: 70px;">{{str_limit($note->text,120)}}</p>

                        <!-- operation of the notes -->
                        <div style="display: inline;">
                            <a href="{{action('NoteController@edit', ['id' => $note->id])}}" class="card-link" > <i class="fa fa-1x fa-pencil" aria-hidden="true"></i>&nbsp;编辑</a>
                        </div>

                        <form action="{{action('NoteController@markdown',['id' => $note->id])}}" method="GET" style="display: inline;">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-link"><i class="fa fa-1x fa-download" aria-hidden="true"></i>&nbsp;下载</button>
                        </form>

                        <form action="{{action('NoteController@destroy',['id' => $note->id])}}" method="post" style="display: inline;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-link" style="color:#C9302C;"><i class="fa fa-1x fa-trash" aria-hidden="true"></i>&nbsp;删除</button>
                        </form>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cloud-download" aria-hidden="true"></i>&nbsp;导出</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{action('EverNoteController@store', ['id' => $note->id])}}"> <img src="{{URL::asset('/images/evernote_logo.png')}}" style="width: 24px; height: 24px;">&nbsp;Evernote</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                            </div>
                        </div>

                        <span style="float: right">
                            <input type="checkbox" class="form-check-input batch" id="batch{{$note->id}}" name="batch[]">
                        </span>

                    </div>
                </div>
            @empty
                <p>No notes</p>
            @endforelse
        </div>

        <!-- paging -->
        <div class="col-lg-8 col-lg-offset-7" style="float: none">
            <div class="col-lg-5">
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
                        @if($pagination->next <= $pagination->total)
                                <li class="page-item"><a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->next])}}">{{$pagination->next}}</a></li>
                        @endif
                        @if($pagination->current == $pagination->total)
                            <li class="page-item disabled">
                        @else
                             <li class="page-item">
                        @endif
                            <a class="page-link" href="{{action('NoteController@index', ['page'=>$pagination->next])}}">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <form id="page-form" action="{{action('NoteController@index')}}" method="GET" style="margin:0px;display:inline;">
                    <div id="div-page" class="input-group">
                        <input type="text" class="form-control"id="page" name="page" placeholder="Page">
                        <span class="input-group-btn">
       	                    <button type="submit" class="btn btn-primary" onclick="
                                event.preventDefault();
                                document.getElementById('page-form').submit();">
                                Go
                        </button>
                    </span>
                    </div>
                </form>
            </div>
            <div class="col-lg-1">
                <span class="text-center">{{$pagination->total}}</b><br><small>Pages</small></span>
            </div>

        </div>
    </div>
@endsection