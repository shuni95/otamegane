@extends('admin.layouts.base')

@section('title', 'Add Source')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>Add Sources</h2></div>

  <form class="ui form" method="POST" action="{{ route('sources.add') }}">
    {{ csrf_field() }}

    <div class="field">
      <label>Name</label>
      <input type="text" name="name">
    </div>

    <div class="field">
      <label>Url</label>
      <input type="text" name="url">
    </div>

    <button class="ui green button" type="submit">Add</button>
  </form>

@endsection
