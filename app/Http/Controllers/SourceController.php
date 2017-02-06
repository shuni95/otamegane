<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddSourceRequest;

use App\Source;

class SourceController extends Controller
{
    public function index()
    {
        $sources = Source::all();

        return view('admin.source.index', compact('sources'));
    }

    public function create()
    {
        return view('admin.source.add');
    }

    public function store(AddSourceRequest $request)
    {
        Source::create([
            'name' => request('name'),
            'url'  => request('url'),
        ]);

        return redirect()->route('sources.index')->with('success_message', 'Source added successfully.');
    }
}
