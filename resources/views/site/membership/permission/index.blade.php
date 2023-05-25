@extends('layouts.user')
@section('meta')
  <title> Bir Kitap Bul | İkinci El Ücretsiz Kitap Platformu | İzinler Ve Ayarlar</title>
  <meta name="keywords" content="">
  <meta name="description" content="" />
@endsection
@section('content')
  <div class="questions-settings">
    <div class="settings-header">
      <h2>İzinler Ve Ayarlar</h2>
    </div>
    <div class="settings-body">
      @foreach($all_permissions as $permission)
        <div class="switch-block">
          <label class="f-switch is-accent">
            <input type="checkbox" class="is-switch permission-switch" {{@$my_perissions[$permission->id] ? 'checked' : ''}} data-id="{{$permission->uuid}}">
            <i></i>
          </label>
          <div class="meta">
            <span>{{$permission->title}}</span>
            <span>{{$permission->description}}</span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@push('page-scripts')
  <script>
    // Ayarlar
    $(document).on("change",".permission-switch",function() {
      var id= $(this).attr('data-id');
      var value = 0;
      if(this.checked) {
        value = 1;
      }
      $.ajax({
        url: '{{url('hesabim/izin/guncelle')}}',
        data: {id: id, _token:csrf_token, is_allow: value},
        type: 'POST',
        success: function (data) {
          // Hata varsa ekrana bas!
          if(!data.success) {
            $.each(data.errors, function( index, value ) {
              toasts.service.error('', 'mdi mdi-book-open-page-variant', value, 'bottomCenter', 2500);
            });
          }else {
            toasts.service.success('', 'mdi mdi-book-open-page-variant', 'İşlemin başarılı bir şekilde tamamlandı!', 'bottomCenter', 2500);
            $('.modal.is-active').removeClass('is-active');
            element.fadeOut('slow');
          }

        },
        error: function (request, status, error) {
        }
      });

    });
  </script>
@endpush