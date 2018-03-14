@extends('layout')

@section('head')
    @parent
    <meta property="og:image" content="{{ asset($nendoroid->getImagePath(0)) }}">
@endsection

@section('content')
    <?php /** @var \App\Nendoroid $nendoroid */ ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <a target="_blank" href="{{ asset($nendoroid->getImagePath(0)) }}">
                        <img src="{{ asset($nendoroid->getImagePath(0)) }}" class="col-12">
                    </a>
                </div>

                <div class="col-sm-8">
                    <dt>Series</dt>
                    <dd>
                        {{ $nendoroid->series }}<br>
                        <a href="{{ route('nendoroid-series', [str_slug($nendoroid->series)]) }}">
                            See all {{ count($seriesNendoroids) }} Nendoroids in the series
                        </a>
                    </dd>

                    <dt>Release Date</dt>
                    <dd>{{ $nendoroid->release_date }}</dd>

                    <dt>Announcement Date</dt>
                    <dd>{{ $nendoroid->announcement_date }}</dd>

                    <dt>Other links</dt>
                    <dd>
                        <a href="{{ route('nendoroids-search', ['q' => trim(explode(':', $nendoroid->getCleanedName())[0])]) }}">
                            Search for more {{ trim(explode(':', $nendoroid->getCleanedName())[0]) }} Nendoroids
                        </a>
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <br>

    <h2>
        All images
    </h2>
    <div class="card-columns">
        @foreach ($nendoroid->getAvailableImages() as $imageIndex)
            <div class="card">
                <div class="card-body">
                    <a target="_blank" href="{{ asset($nendoroid->getImagePath($imageIndex)) }}">
                        <img class="card-img" src="{{ asset($nendoroid->getImagePath($imageIndex)) }}">
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@endsection