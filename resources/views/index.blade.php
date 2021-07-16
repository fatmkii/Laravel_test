<!DOCTYPE html>
<html lang="zh_CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> @yield('title',config('app.name'))</title>
</head>

<body>
    <div id='app' class='container'>
        <app></app>
        <navigation></navigation>
        <router-view></router-view>
    </div>

</body>


<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

</html>
