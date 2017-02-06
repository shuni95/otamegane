<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddMangaRequest;

use App\Manga;
use App\Source;

class MangaController extends Controller
{
    public function index()
    {
        $mangas = Manga::all();

        return view('admin.manga.index', compact('mangas'));
    }

    public function create()
    {
        $sources = Source::all();

        return view('admin.manga.add', compact('sources'));
    }

    public function store(AddMangaRequest $request)
    {
        $manga = Manga::create(['name' => request('name')]);
        $manga->sources()->attach(request('sources'));

        return redirect()->route('mangas.index')->with('success_message', 'Manga added successfully');
    }

    public function show($id)
    {
        $manga = Manga::with('sources')->find($id);

        return view('admin.manga.show', compact('manga'));
    }
}
