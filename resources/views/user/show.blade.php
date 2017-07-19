@extends('layouts.app')

@section('content')
    <div class="container col-lg-offset-3">
        <form action="{{action('UserController@update', $user->id)}}" method="post">
            <!-- CSRF Protection -->
             {{ csrf_field() }}
             {{ method_field('PATCH') }}

            <!-- Name -->
            <div class="form-group row">
                <label for="author" class="col-lg-1 col-form-label">Name</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="{{$user->name}}" value="{{$user->name}}"
                    >
                </div>
            </div>

            <!-- Email -->
            <div class="form-group row">
                <label for="email" class="col-lg-1 col-form-label">Email</label>
                <div class="col-lg-4">
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="{{$user->email}}" value="{{$user->email}}"
                    >
                </div>
            </div>

            <!-- Registration date -->
            <div class="form-group row">
                <label for="dateTime" class="col-lg-1 col-form-label">Registration time</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="created_at" name="created_at"
                           value="{{$user->created_at}}" readonly
                    >
                </div>
            </div>



            <div class="col-lg-offset-4 col-lg-12">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>

        <H4>Danger Zone</H4>
        <div class="col-lg-6" style="border: solid 1px; border-color: #d73a49; padding-top: 5px; padding-bottom: 5px;">
            <form id="logout-form" action="{{ action('UserController@destroy', $user->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="col-lg-9">
                    Once you delete a account, there is no going back. Please be certain.
                </div>
                <div class="col-lg-3 ">
                    <button type="button submit" class="btn btn-danger">Destroy</button>
                </div>
            </form>
        </div>


    </div>
@endsection