@extends('admin.layouts.base')

@section('title', 'Telegram User Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue segment"><h2>List of Telegram Users</h2></div>

  <table class="ui blue table">
    <thead>
      <th>#</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
      <th>Actions</th>
    </thead>
    <tbody>
    @foreach($telegram_users as $telegram_user)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $telegram_user->first_name }}</td>
        <td>{{ $telegram_user->last_name }}</td>
        <td>{{ $telegram_user->username }}</td>
        <td>
          <a href="{{ route('telegram_users.subscriptions', ['id' => $telegram_user->id]) }}" title="Subscriptions"><i class="ui book icon"></i></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
