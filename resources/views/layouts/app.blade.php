<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- bootstrap css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- font-awesome css -->
    <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <span><img src="{{URL::asset('/images/logo.png')}}" style="height: 30px; width: 30px;"></span>
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-slideshare" aria-hidden="true"></i>&nbsp;{{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <form action="{{action('ClippingController@store')}}" method="POST" enctype="multipart/form-data" style="border-bottom: solid 1px #d3e0e9;">
                                            <!-- CSRF Protection -->
                                            {{ csrf_field() }}

                                            <input type="file" class="form-control-file" id="uploadFile" name="uploadFile">
                                            <button type="submit" class="btn btn-primary btn-link">
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>Upload
                                            </button>
                                            <input type="checkbox" class="form-check-input" id="isDownloadMarkdown" name="isDownloadMarkdown"><small>MarkDown</small>
                                        </form>
                                        <a href="{{ route('home') }}">
                                            <i class="fa fa-home" aria-hidden="true"></i>&nbsp;Home
                                        </a>

                                        <a href="{{ action('NoteController@index') }}">
                                            <i class="fa fa-book" aria-hidden="true"></i>&nbsp;Notes
                                        </a>

                                        <a href="{{ action('UserController@show', ['id'=>Auth::id()]) }}">
                                            <i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Setting
                                        </a>

                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
