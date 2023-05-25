@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-4 col-4">
      <h4 class="page-title">Şifre Değiştir</h4>
    </div>
  </div>

  <form method="post" action="{{route('admin.password.save')}}">
    @csrf
    <div class="row filter-row">
      <div class="col-sm-9 col-md-9">
        <div class="form-group form-focus">
          <label class="focus-label">Yeni Şifre</label>
          <input type="password" class="form-control floating" name="password" value="{{request('password')}}">
        </div>
      </div>

      <div class="col-sm-6 col-md-3">
        <button type="submit" class="btn btn-primary btn-block"> Değiştir </button>
      </div>
    </div>
  </form>
@endsection