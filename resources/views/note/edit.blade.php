@extends('layouts.app')

@section('content')
    <div class="container col-lg-offset-3">
            <form action="{{action('NoteController@update', $note->id)}}" method="post">
                <!-- CSRF Protection -->
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <!-- Title -->
                <div class="form-group row">
                    <label for="author" class="col-lg-1 col-form-label">Title</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" id="title" name="title"
                               placeholder="{{$note->title}}" value="{{$note->title}}"
                        >
                    </div>
                </div>

                <!-- Author -->
                <div class="form-group row">
                    <label for="author" class="col-lg-1 col-form-label">Author</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" id="author" name="author"
                                    placeholder="{{$note->author}}" value="{{$note->author}}"
                            >
                        </div>
                </div>

                <!-- Date time -->
                <div class="form-group row">
                    <label for="dateTime" class="col-lg-1 col-form-label">Date time</label>
                    <div class="col-lg-2">
                        <input type="text" class="form-control" id="dateTime" name="dateTime"
                               placeholder="{{$note->dateTime}}" value="{{$note->dateTime}}"
                        >
                    </div>
                </div>

                <!-- Text -->
                <div class="form-group">
                    <label for="text">Text</label><br>
                    <div class="col-lg-6">
                        <textarea class="form-control" id="text" name="text" rows="6" placeholder="{{$note->text}}">{{$note->text}}</textarea>
                    </div>
                </div>

                <div class="col-lg-offset-4 col-lg-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
    </div>
@endsection