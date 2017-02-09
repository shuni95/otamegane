<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta charset="utf-8">

  <title>@yield('title')</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/semantic.min.css') }}">
  <script src="{{ asset('/jquery-3.1.1.min.js') }}"></script>
  <script src="{{ asset('/semantic.min.js') }}"></script>

  @stack('head')
</head>
<body class="pushable">

  @include('admin.layouts.sidebar')

  <div class="ui fixed inverted main menu">
    <a class="item" id="menu-button">
      <i class="sidebar icon"></i>
      Menu
    </a>
  </div>

  <div class="pusher">
    <div class="ui container" style="padding-top: 5em;">
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script>
  $('#menu-button').click(function(){
    $('.ui.sidebar')
    .sidebar('setting', 'transition', 'overlay')
    .sidebar('toggle');
  });
  </script>
  @stack('scripts')
</body>
</html>
