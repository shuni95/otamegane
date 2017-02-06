@extends('admin.layouts.base')

@section('title', 'Subscriptions')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>Subscriptions of {{ $telegram_user->full_name }}</h2></div>

  <table class="ui table">
    <thead>
      <th>#</th>
      <th>Manga</th>
      <th>Source</th>
      <th>Last chapter notified</th>
      <th>Actions</th>
    </thead>
    <tbody>
      @foreach ($telegram_user->subscriptions as $subscription)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $subscription->manga->name }}</td>
        <td>{{ $subscription->source->name }}</td>
        <td>{{ $subscription->last_chapter }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('telegram_users.index') }}" class="ui button">Back</a>

@endsection

@push('scripts')
<script>
  $('.ui.checkbox').checkbox();
</script>
@endpush
