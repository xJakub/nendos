@extends('layout')

@section('content')
    Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} Nendoroid series

    <div class="text-center">
        <div class="d-inline-block">
            {{ $results->links() }}
        </div>
    </div>

    @foreach($results->chunk(4) as $chunk)
        <div class="row">
            @foreach($chunk as $series)
                <?php /** @var \App\Nendoroid[] $series */ ?>
                <div class="col-sm-3">
                    <div class="card">
                        <img class="card-img-top" src="{{ asset($series[0]->getImagePath(0)) }}">
                        <div class="card-body">
                            <strong><a href="{{ route('nendoroid-series', [str_slug($series[0]->series)]) }}">{{ $series->reverse()[0]->series }}</a></strong><br>
                            {{ $series->count() }} {{ str_plural('Nendoroid', $series->count()) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="text-center">
        <div class="d-inline-block">
            {{ $results->links() }}
        </div>
    </div>
@endsection