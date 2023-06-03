@extends('admin.layouts.app')
@section('content')
  <div class="row">
      <div class="col-sm-7 col-7">
          <h4 class="page-title">Kategoriler</h4>
      </div>
      <div class="col-sm-5 text-right m-b-30">
          <a href="{{route('admin.cargo.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Kargo Ekle</a>
      </div>
  </div>

    <form>
      <div class="row filter-row">
        <div class="col-sm-6 col-md-6">
          <div class="form-group form-focus">
            <label class="focus-label">Kategori adı</label>
            <input type="text" class="form-control floating" name="name" value="{{request('name')}}">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <button type="submit" class="btn btn-primary btn-block"> Ara </button>
        </div>
      </div>
    </form>
  @include('admin.cargo.table', ['items' => $models ?: []])

  <div class="row staff-grid-row mt-3">
    <div class="col-sm-12">
      <div class="see-all text-center">
        {{ $models->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
@endsection
