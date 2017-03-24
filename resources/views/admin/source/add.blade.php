@extends('layouts.app')

@section('title', 'Add Source')

@section('content')
<div class="container">

  @include('admin.error_message')

  <div class="row"><h2>Add Sources</h2></div>

  <form class="form" method="POST" action="{{ route('sources.add') }}">
    {{ csrf_field() }}

    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control">
    </div>

    <div class="form-group">
      <label>Url</label>
      <input type="text" name="url" class="form-control">
    </div>

    <button class="btn btn-success" type="submit">Add</button>
    <a href="{{ route('sources.index') }}" class="btn btn-default">Back</a>
  </form>
</div>
@endsection
