<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddMangaSourceRequest;

use App\Manga;
use App\Source;
use App\MangaSource;

class SourceMangaController extends Controller
{
    public function create($id)
    {
        $source = Source::find($id);

        $mangas = Manga::whereHas('manga_sources', function ($manga_source) use ($source) {
            $manga_source->whereNotIn('manga_source.manga_id', $source->mangas->pluck('id'));
        })->get();

        return view('admin.source.add_manga', compact('source', 'mangas'));
    }

    public function store($id, AddMangaSourceRequest $request)
    {
        $source = Source::find($id);
        $source->mangas()->attach(request('mangas'));

        return redirect()->route('sources.index')->with('success_message', 'Mangas added successfully to the '. $source->name);
    }
}
