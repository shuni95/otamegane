@extends('admin.layouts.base')

@section('title', 'Add Manga')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>Add Manga</h2></div>

  <form class="ui form" method="POST" action="{{ route('mangas.add') }}">
    {{ csrf_field() }}

    <div class="field">
      <label>Name</label>
      <input type="text" name="name">
    </div>

    <div class="field">
      <label>Source that have the manga</label>
    </div>

    <div class="inline field">
      @foreach ($sources as $source)
      <div class="ui toggle checkbox">
        <input type="checkbox" name="sources[]" tabindex="0" class="hidden" value="{{ $source->id }}">
        <label>{{ $source->name }}</label>
      </div>
      @endforeach
    </div>

    <button class="ui green button" type="submit">Add</button>
    <a href="{{ route('mangas.index') }}" class="ui button">Back</a>
  </form>

@endsection

@push('scripts')
<script>
  $('.ui.checkbox').checkbox();
</script>
@endpush
