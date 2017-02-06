<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta charset="utf-8">

  <title>@yield('title')</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/semantic.min.css') }}">
  <script src="{{ asset('/jquery-3.1.1.min.js') }}"></script>
  <script src="{{ asset('/semantic.min.js') }}"></script>
  <style type="text/css">
    .pusher { padding: 10px !important; }
    .pushable { height: auto; }
    #content { height: calc(100% - 42px) !important; margin-bottom: 0px !important;}
  </style>

  @stack('head')
</head>
<body>

  <div class="ui top attached menu">
    <a class="item" id="menu-button">
      <i class="sidebar icon"></i>
      Menu
    </a>
  </div>

  <div class="ui bottom attached segment pushable" id="content">
    @include('admin.layouts.sidebar')

    <div class="pusher">
      <div class="ui container">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
  $('#menu-button').click(function(){
    $('.ui.sidebar').sidebar({
      context: $('.bottom.segment')
    }).sidebar('toggle');
  });
  </script>
  @stack('scripts')
</body>
</html>
