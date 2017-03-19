@extends('admin.layouts.base')

@section('title', 'Add Manga')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>Edit Manga {{ $manga->name }}</h2></div>

  <form class="ui form" method="POST" action="{{ route('mangas.update', ['id' => $manga->id]) }}">
    {{ csrf_field() }}

    <div class="field">
      <label>Name</label>
      <input type="text" name="name" value="{{ $manga->name }}">
    </div>

    <button class="ui green button" type="submit">Update</button>
    <a href="{{ route('mangas.index') }}" class="ui button">Back</a>
  </form>

@endsection

@push('scripts')
<script>
  $('.ui.checkbox').checkbox();
</script>
@endpush
