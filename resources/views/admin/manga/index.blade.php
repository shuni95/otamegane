@extends('admin.layouts.base')

@section('title', 'Manga Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue center aligned segment">
    <h2>List of Manga</h2>
  </div>

  <div class="ui basic right aligned segment">
    <a class="ui blue icon button" href="{{ route('mangas.add_form') }}">
      <i class="book icon"></i> Add Manga
    </a>
  </div>

  <table class="ui blue very compact table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th># subscribers</th>
      <th># subscriptions</th>
      <th># sources</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach($mangas as $manga)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $manga->name }}</td>
        <td>{{ $manga->total_subscribers }}</td>
        <td>{{ $manga->total_subscriptions }}</td>
        <td>{{ $manga->num_sources }}</td>
        <td>
           <a href="{{ route('mangas.show', ['id' => $manga->id ]) }}" title="Show"><i class="eye icon"></i></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
