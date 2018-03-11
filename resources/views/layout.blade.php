<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <meta name="viewport" content="width=512px">
    <title>
        @empty($title)
            Nendos
        @endempty
        @isset($title)
            {{ $title }}
        @endisset
    </title>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Nendos?</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">All Nendoroids</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('nendoroid-series-list') }}">All series</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="{{ route('nendoroids-search') }}">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="q">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <hr>

    @isset($title)
        <h1>{{ $title }}</h1>
    @endisset

    @yield('content')
</div>
</body>
</html>
