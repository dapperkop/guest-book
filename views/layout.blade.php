<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <base href="/" />

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <style type="text/css">
        header {
            margin-bottom: 20px;
        }
        .post {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid gray;
            border-radius: 3px;
        }
    </style>
    @yield('css')

    <title>@yield('title') | GuestBook</title>
</head>

<body>
    <div class="container">
        <header>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="/posts">Main</a>
                </li>
                <li class="list-inline-item">
                    <a href="/posts/add">Add new post</a>
                </li>
            </ul>
        </header>
        <h1>@yield('title')</h1>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="js/bootstrap.min.js"></script>
    @yield('js')
</body>

</html>