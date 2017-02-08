@extends('admin.layouts.base')

@section('title', 'Add Source')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>{{ $source->name }} - Add Manga</h2></div>

  <form class="ui form" method="POST" action="{{ route('sources.add_manga', ['id' => $source->id]) }}">
    {{ csrf_field() }}

    <div class="field">
      <label>Manga</label>

      @foreach ($mangas as $manga)
      <div class="field">
      <div class="ui toggle checkbox">
        <input type="checkbox" name="mangas[]" tabindex="0" class="hidden" value="{{ $manga->id }}">
        <label>{{ $manga->name }}</label>
      </div>
      </div>
      @endforeach
    </div>

    <button class="ui green button" type="submit">Add</button>
    <a href="{{ route('sources.index') }}" class="ui button">Back</a>
  </form>

@endsection

@push('scripts')
<script>
  $('.ui.checkbox').checkbox();
</script>
@endpush
