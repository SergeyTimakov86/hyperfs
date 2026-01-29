<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link href="/static/bootstrap.css" rel="stylesheet">
    <link href="/static/fa.css" rel="stylesheet">
    <link href="/static/main.css" rel="stylesheet">
    <script src="/static/bootstrap.js"></script>
    <script type="module" src="/static/auth.js"></script>
    <script type="module" src="/static/form.js"></script>
    <script type="module" src="/static/table-sort.js"></script>
</head>
<body>
    @section('navbar')
    @show
    <div class="container-fluid">
        @yield('content')
    </div>
</body>
</html>