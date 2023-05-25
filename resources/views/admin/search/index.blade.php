@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-4 col-4">
      <h4 class="page-title">Yapılan Aramalar</h4>
    </div>
  </div>

  <form>
    <div class="row filter-row">
      <div class="col-sm-6 col-md-6">
        <div class="form-group form-focus">
          <label class="focus-label">Kitap Adı Ya da Yazar</label>
          <input type="text" class="form-control floating" name="name" value="{{request('name')}}">
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="form-group form-focus select-focus">
          <label class="focus-label">Durum</label>
          <select class="select floating" name="status">
            <option value="">Tümü</option>
          </select>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <button type="submit" class="btn btn-primary btn-block"> Ara </button>
      </div>
    </div>
  </form>

  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-border custom-table m-b-0">
          <thead>
          <tr>
            <th>ID</th>
            <th>IP Adresi</th>
            <th>Kullanıcı</th>
            <th>Kitap</th>
            <th>Yazar</th>
            <th>Kategori</th>
            <th>Tip</th>
            <th>Şehir</th>
            <th>İlçe</th>
            <th>Tarih</th>
          </tr>
          </thead>
          <tbody>
          @foreach($models as $model)
            <tr>
              <td>#{{$model->id}}</td>
              <td>{{$model->ip_address}}</td>
              <td>{{@$model->user ?$model->user->full_name() : ''}}</td>
              <td>{{$model->book_name}}</td>
              <td>{{$model->author}}</td>
              <td>{{@$model->category->name ? : ''}}</td>
              <td>{{@$model->type==\App\Model\Search::TYPE_ALL ? 'Tümü' : 'İstenebilir Durumda Olanlar'}}</td>
              <td>{{@$model->city->name ? : ''}}</td>
              <td>{{@$model->town->name ? : ''}}</td>
                <td>{{$model->created_at()}}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="row staff-grid-row mt-3">
    <div class="col-sm-12">
      <div class="see-all text-center">
        {{ $models->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
@endsection
