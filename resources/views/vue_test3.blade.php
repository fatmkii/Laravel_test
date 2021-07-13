<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <title>Vue test 3</title>
</head>

<style>
    [v-cloak] {
        display: none;
    }
</style>

<body>
    <div id="app" v-cloak>

        <my-component></my-component>
    </div>
</body>

<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
