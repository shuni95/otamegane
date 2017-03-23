@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
  <div class="row">
    <h1>Dashboard</h1>
  </div>
  <div class="row">
    <a class="btn btn-lg" href="{{ route('sources.index') }}" title="Sources">
      <span class="glyphicon glyphicon-leaf" aria-hidden="true"></span> Sources
    </a>
  </div>
  <div class="row">
    <a class="btn btn-lg" href="{{ route('mangas.index') }}" title="Mangas">
      <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Mangas
    </a>
  </div>
  <div class="row">
    <a class="btn btn-lg" href="{{ route('telegram_chats.index') }}" title="Telegram Chats">
      <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Telegram Chats
    </a>
  </div>
  <div class="row">
    <a class="btn btn-lg" href="{{ route('suggestions.index') }}" title="Suggestions">
      <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> Suggestions
    </a>
  </div>
</div>
@endsection
