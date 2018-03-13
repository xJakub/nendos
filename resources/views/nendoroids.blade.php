@extends('layout')

@section('head')
    @parent

    @if($nendoroids->count())
        <meta property="og:image" content="{{ asset($nendoroids[0]->getImagePath(0)) }}">
    @endif
@endsection

@section('content')
    Showing {{ $nendoroids->firstItem() }} to {{ $nendoroids->lastItem() }} of {{ $nendoroids->total() }} Nendoroids

    <div class="text-center">
        <div class="d-inline-block">
            {{ $nendoroids->links() }}
        </div>
    </div>

    @foreach($nendoroids->chunk(4) as $chunk)
        <div class="row">
            @foreach($chunk as $nendoroid)
                <?php /** @var \App\Nendoroid $nendoroid */ ?>
                <div class="col-sm-3">
                    <div class="card">
                        <a href="{{ $nendoroid->getLink() }}">
                            <img class="card-img-top" src="{{ asset($nendoroid->getImagePath(0)) }}">
                        </a>
                        <div class="card-body">
                            #{{ $nendoroid->number }}

                            <a href="{{ $nendoroid->getLink() }}">
                                <strong>{{ $nendoroid->getCleanedName() }}</strong>
                            </a><br>
                            <a href="{{ route('nendoroid-series', [str_slug($nendoroid->series)]) }}">{{ $nendoroid->series }}</a><br>
                            Release Date: {{ $nendoroid->release_date }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="text-center">
        <div class="d-inline-block">
            {{ $nendoroids->links() }}
        </div>
    </div>
@endsection