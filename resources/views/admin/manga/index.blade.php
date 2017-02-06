@extends('admin.layouts.base')

@section('title', 'Manga Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue segment"><h2>List of Manga</h2></div>

  <div class="ui basic right aligned segment">
    <a class="ui blue button" href="{{ route('mangas.add_form') }}">New Manga</a>
  </div>

  <table class="ui blue table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach($mangas as $manga)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $manga->name }}</td>
        <td>
           <a href="{{ route('mangas.show', ['id' => $manga->id ]) }}" title="Show"><i class="eye icon"></i></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
