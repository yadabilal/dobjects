@extends('admin.layouts.app')
@section('content')
  <form method="post"  enctype="multipart/form-data" action="{{route('admin.setting.save')}}">
    @csrf
  <div class="row">
    <div class="col-sm-4 col-4">
      <h4 class="page-title">Ayarlar</h4>
    </div>
    <div class="col-sm-8 col-8 text-right m-b-20">
      <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Kaydet</button>
    </div>
  </div>

    <div class="row filter-row">
      <div class="col-sm-3 col-md-3">
        <div class="form-group form-focus">
          <label class="focus-label">Anahtar</label>
          <input type="text" class="form-control floating" name="new_key" value="{{request('new_key')}}">
        </div>
      </div>
      <div class="col-sm-9 col-md-9">
        <div class="form-group form-focus">
          <label class="focus-label">Değer</label>
          <input type="text" class="form-control floating" name="new_value" value="{{request('new_value')}}">
        </div>
      </div>
    </div>
      <div class="row filter-row">
          <div class="col-sm-3 col-md-3">
              <div class="form-group form-focus">
                  <label class="focus-label">LOGO</label>
                  <input type="text" class="form-control floating" value="logo" disabled>
              </div>
          </div>
          <div class="col-sm-6 col-md-6">
              <div class="form-group">
                  <input class="form-control" type="file" name="logo" accept="image/x-png,image/gif,image/jpeg">
              </div>
          </div>
          @if($logo)
              <div class="col-md-3 col-sm-3 col-4 col-lg-3 col-xl-2">
                  <div class="product-thumbnail" style="margin-top: 0px!important; ">
                      <img src="{{url('uploads/'.$logo->value)}}" class="img-thumbnail img-fluid" alt="">
                  </div>
              </div>
          @endif
      </div>
    @foreach($models as $model)
      <div class="row filter-row">
        <div class="col-sm-3 col-md-3">
          <div class="form-group form-focus">
            <label class="focus-label">{{$model->param}}</label>
            <input type="text" class="form-control floating" value="{{$model->param}}" disabled>
          </div>
        </div>
        <div class="col-sm-9 col-md-9">
          <div class="form-group form-focus">
            <label class="focus-label">Değer</label>
            <input type="text" class="form-control floating" name="{{$model->param}}" value="{{$model->value}}">
          </div>
        </div>
      </div>
      @endforeach
  </form>

@endsection
