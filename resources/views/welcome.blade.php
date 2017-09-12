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
                        <a href="{{ url('/home') }}">@lang('welcome.Home')</a>
                    @else
                        <a href="{{ url('/login') }}">@lang('welcome.Login')</a>
                        <a href="{{ url('/register') }}">@lang('welcome.Register')</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <img src="{{URL::asset('/images/logo.png')}}">
                <div class="title m-b-md">
                    {{ config('app.name', 'Laravel') }}
                </div>
                <h5>@lang('welcome.Introduce')</h5>

                <div id="social" style="color: #1b95e0">
                    <a type="button"  class="btn btn-secondary btn-sm" href="https://github.com/badtudou"><i class="fa fa-github fa-2x" aria-hidden="true"></i>&nbsp;@lang('welcome.GitHub')</a>
                    <a type="button"  class="btn btn-secondary btn-sm" href="http://weibo.com/badtudou"><i class="fa fa-weibo fa-2x" aria-hidden="true"></i>&nbsp;@lang('welcome.Weibo')</a>
                    <a type="button"  class="btn btn-secondary btn-sm" href="https://www.facebook.com/badtudou"><i class="fa fa-facebook fa-2x" aria-hidden="true"></i>&nbsp;@lang('welcome.Facebook')</a>
                </div>
                

                <div id="div-footer" class="col-lg-12 text-center" style="margin-top: 50px">
                    <div id="div-footer--copyright">
                        <strong>Copyright © 2016 BadTudou. All rights reserved.<br/></strong>
                    </div>
                    <div id="div-footer--ICP">
                        <strong>渝ICP备16010823号-1 <a href="http://www.miit.gov.cn/">工信部备案查询</a></strong>
                    </div>
                </div>
            </div>
        </div>
        

    </body>
</html>
