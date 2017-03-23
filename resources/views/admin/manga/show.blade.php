@extends('layouts.app')

@section('title', 'Add Manga')

@section('content')
<div class="container">

  @include('admin.error_message')

  <div class="row"><h2>{{ $manga->name }}</h2></div>

  <div class="row">
    <h3>Sources</h3>
    <ul>
      @foreach ($manga->sources as $source)
      <li>
        <a href="{{ $source->url }}">{{ $source->name }}</a>
      </li>
      @endforeach
    </ul>
  </div>

  <a href="{{ route('mangas.index') }}" class="btn btn-default">Back</a>
</div>
@endsection
