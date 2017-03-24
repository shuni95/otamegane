@extends('layouts.app')

@section('title', 'Subscriptions')

@section('content')
<div class="container" id="app">
  <div class="row"><h2>Subscriptions of {{ $telegram_chat->full_name }}</h2></div>

  <table class="table">
    <thead>
      <th>#</th>
      <th>Manga</th>
      <th>Source</th>
      <th>Last chapter notified</th>
    </thead>
    <tbody>
      @foreach ($telegram_chat->subscriptions as $subscription)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $subscription->manga->name }}</td>
        <td>{{ $subscription->source->name }}</td>
        <td>{{ $subscription->last_chapter }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('telegram_chats.index') }}" class="btn btn-default">Back</a>
</div>
@endsection
