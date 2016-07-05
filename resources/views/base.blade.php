<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    @yield('head')
</head>
<body>
@include('header')
<div class="container">
    @yield('content')
</div>
</body>
@yield('footer')
</html>