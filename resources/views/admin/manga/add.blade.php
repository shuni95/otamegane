@extends('layouts.app')

@section('title', 'Add Manga')

@section('content')
<div class="container" id="app">
  @include('admin.error_message')

  <div class="row"><h2>Add Manga</h2></div>

  <form class="form" method="POST" action="{{ route('mangas.add') }}">
    {{ csrf_field() }}

    <div class="form-group">
      <label class="control-label">Name</label>
      <input class="form-control" type="text" name="name">
    </div>
    <div class="form-group">
      <label>Source that have the manga:</label>
    </div>
    <div class="form-group">
      @foreach ($sources as $source)
      <div class="checkbox">
        <label><input type="checkbox" name="sources[]" value="{{ $source->id }}">{{ $source->name }}</label>
      </div>
      @endforeach
    </div>
    <div class="form-group">
      <button class="btn btn-success" type="submit">Add</button>
      <a href="{{ route('mangas.index') }}" class="btn btn-default">Back</a>
    </div>
  </form>
</div>
@endsection
