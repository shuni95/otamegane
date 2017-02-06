<div class="ui inverted segment">
  <div class="ui inverted menu">
    <div class="ui inverted secondary pointing menu">
      <a class="active item" href="{{ url('/') }}">
        <i class="home icon"></i> Home
      </a>
    </div>
    <div class="right menu">
      <div class="ui buttons">
        <div class="ui button">{{ Auth::guard('admin')->user()->user->username }}</div>
        <div class="ui floating dropdown icon button">
          <i class="dropdown icon"></i>
          <div class="menu">
            <div class="item">
            <form id="logout-form" action="{{ route('app.trainers.logout') }}" method="POST">
            {{ csrf_field() }}
            <button class="ui red button" type="submit">Logout</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $('.ui.dropdown').dropdown();
</script>
@endpush
