@extends('layouts.app')

@section('title', 'Add Source')

@section('content')
<div class="container">

  @include('admin.error_message')

  <div class="row"><h2>{{ $source->name }} - Add Manga</h2></div>

  <form class="form" method="POST" action="{{ route('sources.add_manga', ['id' => $source->id]) }}">
    {{ csrf_field() }}

    <div class="field">
      <label>Mangas</label>

      @foreach ($mangas as $manga)
      <div class="form-group">
        <div class="checkbox">
          <label><input type="checkbox" name="mangas[]" value="{{ $source->id }}">{{ $manga->name }}</label>
        </div>
      </div>
      @endforeach
    </div>

    <button class="btn btn-success" type="submit">Add</button>
    <a href="{{ route('sources.index') }}" class="btn btn-default">Back</a>
  </form>
</div>
@endsection
