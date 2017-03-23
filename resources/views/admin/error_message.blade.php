@if (session('errors'))
  <div class="alert alert-danger">
    <h4>Whoops!</h4>
    <ul class="list">
    @foreach (session('errors')->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
  </div>
@endif
