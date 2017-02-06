@if (session('success_message'))
  <div class="ui success message">
    <i class="close icon"></i>
    <div class="header">
      {{ session('success_message') }}
    </div>
  </div>
@endif

@push ('scripts')
<script>
  $('.message .close').on('click', function() {
    $(this).closest('.message').transition('fade');
  });
</script>
@endpush