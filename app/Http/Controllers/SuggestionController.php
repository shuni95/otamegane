<?php

namespace App\Http\Controllers;

use App\Suggestion;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with('source')->get();

        return view('admin.suggestions.index', compact('suggestions'));
    }
}
