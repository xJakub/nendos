@extends('layout')

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
                        <img class="card-img-top" src="{{ asset($nendoroid->getImagePath(0)) }}">
                        <div class="card-body">
                            #{{ $nendoroid->number }}
                            <strong>{{ preg_replace("' \([^\)]+\)$'", '', str_replace('Nendoroid ', '', $nendoroid->name)) }}</strong><br>
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