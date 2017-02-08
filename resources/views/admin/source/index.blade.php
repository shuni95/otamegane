@extends('admin.layouts.base')

@section('title', 'Source Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue segment"><h2>List of Sources</h2></div>

  <div class="ui basic right aligned segment">
    <a class="ui blue button" href="{{ route('sources.add_form') }}">New Source</a>
  </div>

  <table class="ui blue table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th>Url</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach($sources as $source)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $source->name }}</td>
        <td><a href="{{ $source->url }}">{{ $source->url }}</a></td>
        <td>
          <a href="{{ route('sources.add_manga_form', ['id' => $source->id]) }}" title="Add Manga"><i class="book icon"></i></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
