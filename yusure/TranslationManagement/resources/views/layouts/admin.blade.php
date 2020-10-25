<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Backend management </title>
  <link href="{{asset('vendors/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/animate/animate.css')}}" rel="stylesheet">
  @yield('css')
  <link href="{{asset('admin/css/style.css?VER=20171222')}}" rel="stylesheet">
</head>
<body class="">
  <div id="wrapper">
    @include('layouts.partials.sidebar')

    <div id="page-wrapper" class="gray-bg">
      <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
          <div class="navbar-header">
              <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
              {{--<form role="search" class="navbar-form-custom" action="search_results.html">
                  <div class="form-group">
                      <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                  </div>
              </form>--}}
          </div>
          <ul class="nav navbar-top-links navbar-right">
              <li>
                  <span class="m-r-sm text-muted welcome-message">{{getUser()->name}}</span>
              </li>

              <li>
                  <a href="https://www.kancloud.cn/yusure/translation_management" target="_blank"> Manual </a>
              </li>

              <li>
                  <a href="{{ url('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                      <i class="fa fa-sign-out"></i> Logout
                      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                  </a>
              </li>
          </ul>
        </nav>
      </div>
      @yield('content')
      <div class="footer">
          <div class="pull-right">
              <strong>Copyright</strong> Yeelight
          </div>
      </div>

    </div>
  </div>
  <script src="{{asset('vendors/jquery/jquery-2.1.1.js')}}"></script>
  <script src="{{asset('vendors/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('vendors/metisMenu/jquery.metisMenu.js')}}"></script>
  <script src="{{asset('vendors/slimscroll/jquery.slimscroll.min.js')}}"></script>
  <script src="{{asset('admin/js/inspinia.js')}}"></script>
  @yield('js')
</body>
</html>