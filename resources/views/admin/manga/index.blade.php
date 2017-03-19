@extends('admin.layouts.base')

@section('title', 'Manga Listing')

@section('content')

  @include('admin.success_message')

  <div class="ui blue center aligned segment">
    <h2>List of Manga</h2>
  </div>


  <div class="ui pagination left aligned menu">
  @php
    $pages = $mangas->lastPage();
    $current = $mangas->currentPage();
  @endphp

  @if ($pages > 10)
    {{-- Use ... --}}
    @if ($current <= 6)
      @for ($page = 1; $page <= 9; $page++)
        <a href="{{ route('mangas.index', ['page' => $page]) }}" class="{{ $current == $page ? 'active' : '' }} item">{{ $page }}</a>
      @endfor
        <a href="#" class="disabled item">...</a>
        <a href="{{ route('mangas.index', ['page' => $pages]) }}" class="item">{{ $pages }}</a>
    @elseif ($current >= ($pages-5))
      <a href="{{ route('mangas.index', ['page' => 1]) }}" class="{{ $current == 1 ? 'active' : '' }} item">1</a>
      <a href="#" class="disabled item">...</a>
      @for ($page = $pages-9; $page <= $pages; $page++)
        <a href="{{ route('mangas.index', ['page' => $page]) }}" class="{{ $current == $page ? 'active' : '' }} item">{{ $page }}</a>
      @endfor
    @else
      <a href="{{ route('mangas.index', ['page' => 1]) }}" class="{{ $current == 1 ? 'active' : '' }} item">1</a>
      <a href="#" class="disabled item">...</a>
      @for ($page = $current-4; $page <= $current + 4; $page++)
        <a href="{{ route('mangas.index', ['page' => $page]) }}" class="{{ $current == $page ? 'active' : '' }} item">{{ $page }}</a>
      @endfor
      <a href="#" class="disabled item">...</a>
        <a href="{{ route('mangas.index', ['page' => $pages]) }}" class="item">{{ $pages }}</a>
    @endif
  @else
    @for ($page = 1; $page <= $pages; $page++)
      <a href="{{ route('mangas.index', ['page' => $page]) }}" class="{{ $current == $page ? 'active' : '' }} item">{{ $page }}</a>
    @endfor
  @endif
  </div>
  <div class="ui basic compact right floated segment" style="padding: 6px; margin: 0;">
    <a class="ui blue icon button" href="{{ route('mangas.add_form') }}">
      <i class="book icon"></i> Add Manga
    </a>

    <a class="ui green icon button" v-show="mangaSelected" v-bind:href="editUrl">
      <i class="edit icon"></i> Edit Manga
    </a>

    <a class="ui gray icon button" v-show="mangaSelected" v-bind:href="showUrl">
      <i class="eye icon"></i> Show Manga
    </a>
  </div>

  <table class="ui blue compact hover table">
    <thead>
      <th>#</th>
      <th>Name</th>
      <th># subscribers</th>
      <th># subscriptions</th>
      <th># sources</th>
    </thead>
    <tbody>
    @foreach($mangas as $manga)
      <tr id="manga-{{ $manga->id }}" v-on:click="selectManga('{{ $manga->id }}')">
        <td>{{ $loop->iteration }}</td>
        <td>{{ $manga->name }}</td>
        <td>{{ $manga->total_subscribers }}</td>
        <td>{{ $manga->total_subscriptions }}</td>
        <td>{{ $manga->num_sources }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <script type="text/javascript">
    var templateShowUrl = "{{ route('mangas.show',['id' => 'manga_id']) }}";
    var templateEditUrl = "{{ route('mangas.edit_form',['id' => 'manga_id']) }}";
  </script>

@endsection

@push('scripts')
<script>
var vm = new Vue(
{
    el: '#container',

    data: {
      appUrl: '',
      mangaSelected: '',
      showUrl: '',
      editUrl: '',
    },

    methods: {
      selectManga: function(id)
      {
        var last_tr = $("#manga-" + this.mangaSelected)
        var tr_selected = $("#manga-" + id)

        if (this.mangaSelected != id) {
          last_tr.removeClass("row-selected");
        }

        tr_selected.toggleClass("row-selected");

        this.mangaSelected = (this.mangaSelected == id) ? 0 : id;
      },
    },

    watch: {
      mangaSelected: function(newValue) {
        if (newValue > 0) {
          this.mangaSelected = newValue;
          this.editUrl = window.templateEditUrl.replace("manga_id", this.mangaSelected);
          this.showUrl = window.templateShowUrl.replace("manga_id", this.mangaSelected);
        }
      }
    },

    mounted() {
      this.appUrl = window.app_url;
    },
});
</script>
@endpush
