<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Nendoroid;

Route::get('/', function () {
    return view('nendoroids', [
        'nendoroids' => \App\Nendoroid::orderBy('release_date', 'desc')->paginate(20)
    ]);
});

Route::get('/series', function () {
    $nendoroids = \App\Nendoroid::orderBy('release_date', 'desc')->get();
    $results = $nendoroids->groupBy(function ($nendoroid) { return str_slug($nendoroid->series); })->sortKeys();
    unset($results['']);

    $perPage = 20;
    $total = count($results);
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $results = $results->slice(($currentPage - 1) * $perPage, $perPage);
    $results = new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, null, ['path' => Request::url(), 'query' => Request::query()]);
    return view('nendoroid-series', [
        'results' => $results
    ]);
})->name('nendoroid-series-list');

Route::get('/series/{series}', function ($series) {
    /** @var \App\Nendoroid[] $nendoroids */
    $results = \App\Nendoroid::orderBy('release_date', 'desc')->get()->filter(function(Nendoroid $nendoroid) use ($series) {
        return str_slug($nendoroid->series) === $series;
    });

    $title = $results->count() ? "{$results->reverse()->values()[0]->series} series Nendoroids" : null;

    $perPage = 20;
    $total = count($results);
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $results = $results->slice(($currentPage - 1) * $perPage, $perPage);
    $results = new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, null, ['path' => Request::url(), 'query' => Request::query()]);

    return view('nendoroids', [
        'title' => $title,
        'nendoroids' => $results
    ]);
})->name('nendoroid-series');


Route::get('/search', function () {
    $mySlug = function ($str) {
        $str = preg_replace('![^\pL\pN\s]+!u', ' ', $str);
        return str_slug($str, ' ');
    };
    $query = Request::get('q');
    $queryParts = array_unique(array_map('trim', explode(' ', $mySlug($query))));

    /** @var \App\Nendoroid[] $nendoroids */
    $results = \App\Nendoroid::orderBy('release_date', 'desc')->get()->filter(function(Nendoroid $nendoroid) use ($queryParts, $mySlug) {
        $normalizedSeries = preg_replace("'([a-z])([A-Z])'", '$1 $2', $nendoroid->series);

        $sources = [$nendoroid->number, $nendoroid->series, $nendoroid->name, $normalizedSeries];
        $sourcesParts = [];
        foreach ($sources as $source) {
            $sourceParts = array_unique(array_map('trim', explode(' ', $mySlug($source))));
            $sourcesParts = array_merge($sourcesParts, $sourceParts);
        }
        foreach ($queryParts as $queryPart) {
            if ($queryPart === '') { continue; }
            if (!in_array($queryPart, $sourcesParts)) {
                return false;
            }
        }
        return true;
    });

    $perPage = 20;
    $total = count($results);
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $results = $results->slice(($currentPage - 1) * $perPage, $perPage);
    $results = new \Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, null, ['path' => Request::url(), 'query' => Request::query()]);

    return view('nendoroids', [
        'title' => "Results for $query",
        'nendoroids' => $results
    ]);
})->name('nendoroids-search');