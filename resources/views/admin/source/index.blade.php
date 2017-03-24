@extends('layouts.app')

@section('title', 'Source Listing')

@section('content')
<div class="container" id="app">

  @include('admin.success_message')

  <div class="row">
    <h2>List of Sources</h2>
  </div>

  <div class="row">
    <a class="btn btn-primary" href="{{ route('sources.add_form') }}">
      <span class="glyphicon glyphicon-leaf" aria-hidden="true"></span> New Source
    </a>
  </div>

  <table class="table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th># subscribers</th>
      <th># subscriptions</th>
      <th># mangas</th>
      <th>Url</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach($sources as $source)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $source->name }}</td>
        <td>{{ $source->total_subscribers }}</td>
        <td>{{ $source->total_subscriptions }}</td>
        <td>{{ $source->num_mangas }}</td>
        <td><a href="{{ $source->url }}">{{ $source->url }}</a></td>
        <td>
          <a href="{{ route('sources.add_manga_form', ['id' => $source->id]) }}" title="Add Manga">
            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          </a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
