@if (session('errors'))
  <div class="ui error message">
    <i class="close icon"></i>
    <div class="header">Whoops!</div>
    <ul class="list">
    @foreach (session('errors')->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
  </div>
@endif

@push ('scripts')
<script>
$(function(){
  $('.message .close').click(function() {
    $(this).closest('.message').transition('fade');
  });
});
</script>
@endpush
