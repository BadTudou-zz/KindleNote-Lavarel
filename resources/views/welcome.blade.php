<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- bootstrap css -->
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <!-- font-awesome css -->
        <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet">
        <!-- Fonts -->
        <link href="../../public/css/Raleway.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <img src="{{URL::asset('/images/logo.png')}}">
                <div class="title m-b-md">
                    {{ config('app.name', 'Laravel') }}
                </div>
                <h5>Export your kindle highlights and notes !</h5>

                {{--<div class="links">--}}
                    {{--<a href="https://laravel.com/docs">Documentation</a>--}}
                    {{--<a href="https://laracasts.com">Laracasts</a>--}}
                    {{--<a href="https://laravel-news.com">News</a>--}}
                    {{--<a href="https://forge.laravel.com">Forge</a>--}}
                    {{--<a href="https://github.com/laravel/laravel">GitHub</a>--}}
                {{--</div>--}}

                <div id="social" style="color: #1b95e0">
                    <a type="button"  class="btn btn-secondary btn-sm" href="https://github.com/badtudou"><i class="fa fa-github fa-2x" aria-hidden="true"></i>&nbsp;GitHub</a>
                    <a type="button"  class="btn btn-secondary btn-sm" href="https://www.zhihu.com/people/du-xiao-dou-"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i>&nbsp;Twitter</a>
                    <a type="button"  class="btn btn-secondary btn-sm" href="https://www.zhihu.com/people/du-xiao-dou-"><i class="fa fa-facebook fa-2x" aria-hidden="true"></i>&nbsp;Facebook</a>
                </div>
            </div>
        </div>

    </body>
</html>
