<!DOCTYPE html>
<html lang="zh-Hant">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="For a Better BookWalker Search Experience">
    <title>@yield('title', 'BookWalker 探索號')</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  </head>
  <body>
    <main class="container-fluid py-3">
      @yield('main')
    </main>

    <footer class="text-center">
      <a href="https://github.com/bepsvpt-me/bookwalker">GitHub</a>
      <p>bepsvpt.me © {{ date('Y') }}</p>
    </footer>
  </body>
</html>
