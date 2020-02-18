<!DOCTYPE html>
<html lang="zh-Hant">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BookWalker 探索號')</title>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css?v=4.4.1') }}">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  </head>
  <body>
    <main class="container-fluid pt-3">
      @yield('main')
    </main>

    <footer class="text-center">
      <a href="https://github.com/bepsvpt-me/bookwalker">GitHub</a>
      <p>bepsvpt.me © {{ date('Y') }}</p>
    </footer>

{{--    <script src="{{ mix('/js/app.js') }}" defer></script>--}}
  </body>
</html>
