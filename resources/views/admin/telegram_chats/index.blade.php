@extends('admin.layouts.base')

@section('title', 'Telegram Chat Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue center aligned segment"><h2>List of Telegram Chats</h2></div>

  <table class="ui blue very compact table">
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
    @foreach($telegram_chats as $telegram_chat)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $telegram_chat->first_name }}</td>
        <td>{{ $telegram_chat->last_name }}</td>
        <td>{{ $telegram_chat->username }}</td>
        <td>{{ $telegram_chat->title }}</td>
        <td>{{ $telegram_chat->type }}</td>
        <td>
          <a href="{{ route('telegram_chats.subscriptions', ['id' => $telegram_chat->chat_id]) }}" title="Subscriptions"><i class="ui book icon"></i></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
