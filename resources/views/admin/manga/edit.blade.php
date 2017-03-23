@extends('layouts.app')

@section('title', 'Add Manga')

@section('content')
<div class="container">
  @include('admin.error_message')

  <div class="row"><h2>Edit Manga {{ $manga->name }}</h2></div>

  <form class="form" method="POST" action="{{ route('mangas.update', ['id' => $manga->id]) }}">
    {{ csrf_field() }}
    <div class="form-group">
      <label>Name</label>
      <input class="form-control" type="text" name="name" value="{{ $manga->name }}">
    </div>
    <div class="form-group">
      <button class="btn btn-success" type="submit">Update</button>
      <a href="{{ route('mangas.index') }}" class="btn btn-default">Back</a>
    </div>
  </form>
</div>
@endsection
