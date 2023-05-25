@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-4 col-4">
      <h4 class="page-title">Sepetteki Ürünler</h4>
    </div>
  </div>

  <form>
    <div class="row filter-row">
      <div class="col-sm-6 col-md-6">
        <div class="form-group form-focus">
          <label class="focus-label">Ürün Adı Yazın</label>
          <input type="text" class="form-control floating" name="name" value="{{request('name')}}">
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
            <th>Görsel</th>
            <th>Ürün Adı</th>
            <th>Sepete Ekleyen Kullanıcı</th>
            <th>Ekleme Adeti</th>
            <th>Eklenme Tarih</th>
          </tr>
          </thead>
          <tbody>
          @foreach($models as $model)
            <tr>
              <td>#{{$model->id}}</td>
              <td>
                <div class="product-det">
                  <img src="{{$model->product->image()}}" alt="" style="max-height: 100%;">
                </div>
              </td>
              <td>{{$model->product->name}}</td>
              <td>{{$model->user->full_name()}}</td>
              <td>{{$model->quantity}} Adet</td>
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
