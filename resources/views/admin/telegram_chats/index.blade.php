@extends('layouts.app')

@section('title', 'Telegram Chat Listing')

@section('content')
<div class="container" id="app">

  @include('admin.success_message')

  <div class="row"><h2>List of Telegram Chats</h2></div>

  <table class="table">
    <thead>
      <th>#</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
      <th>Title</th>
      <th>Type</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach ($telegram_chats as $telegram_chat)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $telegram_chat->first_name }}</td>
        <td>{{ $telegram_chat->last_name }}</td>
        <td>{{ $telegram_chat->username }}</td>
        <td>{{ $telegram_chat->title }}</td>
        <td>{{ $telegram_chat->type }}</td>
        <td>
          <a href="{{ route('telegram_chats.subscriptions', ['id' => $telegram_chat->chat_id]) }}" title="Subscriptions">
            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          </a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
