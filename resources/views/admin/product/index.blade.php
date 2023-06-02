@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-7 col-7">
      <h4 class="page-title">Ürünler</h4>
    </div>
      <div class="col-sm-5 text-right m-b-30">
          <a href="{{route('admin.product.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Ürün Ekle</a>
      </div>
  </div>

  <form>
    <div class="row filter-row">
      <div class="col-sm-6 col-md-6">
        <div class="form-group form-focus">
          <label class="focus-label">Ürün adı yazın...</label>
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

  @include('admin.product.table', ['items' => $models ?: []])
  <div class="row staff-grid-row mt-3">
    <div class="col-sm-12">
      <div class="see-all text-center">
        {{ $models->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
@endsection
