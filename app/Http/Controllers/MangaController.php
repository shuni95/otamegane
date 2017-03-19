<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddMangaRequest;
use App\Http\Requests\Admin\UpdateMangaRequest;

use App\Manga;
use App\Source;

class MangaController extends Controller
{
    public function index()
    {
        $mangas = Manga::with('manga_sources')->paginate(10);

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

    public function edit($id)
    {
        $manga = Manga::find($id);

        return view('admin.manga.edit', compact('manga'));
    }

    public function update($id, UpdateMangaRequest $request)
    {
        $manga = Manga::find($id);
        $manga->name = request('name');
        $manga->save();

        return redirect()->route('mangas.index')->with('success_message', 'Manga updated successfully');
    }
}
