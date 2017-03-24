@extends('layouts.app')

@section('title', 'Source Listing')

@section('content')
<div class="container">
  <div class="row"><h2>List of Suggestions</h2></div>

  <table class="table">
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
</div>
@endsection
