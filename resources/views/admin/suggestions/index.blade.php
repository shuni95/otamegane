@extends('admin.layouts.base')

@section('title', 'Source Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue center aligned segment"><h2>List of Suggestions</h2></div>

  <table class="ui blue very compact table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th>Source</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach ($suggestions as $suggestion)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $suggestion->name }}</td>
        <td>{{ $suggestion->source->name }}</td>
        <td>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
