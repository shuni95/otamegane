@extends('admin.layouts.base')

@section('title', 'Dashboard')

@section('content')

<div class="ui blue segment">
  <h1>Dashboard</h1>
</div>

<div class="ui six stackable cards">
  <div class="blue card">
    <div class="content">
      <a class="header" href="{{ route('sources.index') }}" title="Sources">
        <i class="ui blue huge tag icon"></i>
      </a>
    </div>
  </div>

  <div class="blue card">
    <div class="content">
      <a class="header" href="{{ route('mangas.index') }}" title="Mangas">
        <i class="ui huge blue book icon"></i>
      </a>
    </div>
  </div>
</div>

@endsection
