@extends('admin.layouts.base')

@section('title', 'Add Manga')

@section('content')

  @include('admin.error_message')

  <div class="ui basic segment"><h2>{{ $manga->name }}</h2></div>

  <div class="ui green segment">
    <h3 class="ui header">Sources</h3>

    <div class="ui list">
      @foreach ($manga->sources as $source)
      <div class="item">
        <div class="content">
          <a href="{{ $source->url }}">{{ $source->name }}</a>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <a href="{{ route('mangas.index') }}" class="ui button">Back</a>

@endsection

@push('scripts')
<script>
  $('.ui.checkbox').checkbox();
</script>
@endpush
