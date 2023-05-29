@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-12">
      <h4 class="page-title">{{!$model->id ? 'Sözleşme Ekle': $model->title.' Güncelle'}} </h4>
    </div>
  </div>
  <form method="post" action="{{route('admin.page.save')}}">
    @csrf
      <input type="hidden" value="{{$model->id}}" name="id" id="productId">
      <input type="hidden" value="contract" name="forWhat" id="forWhat">
    <div class="card-box">
      <h3 class="card-title">Bilgiler</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-focus">
                    <label class="focus-label">Başlık</label>
                    <input type="text" class="form-control floating title" name="title" value="{{old('title', @$model->title)}}" maxlength="100">
                </div>
                @error('title')
                <small class="form-text text-muted">{{$message}}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group form-focus">
                    <label class="focus-label">Durum</label>
                    <select class="select floating" name="status">
                        <option value="">Seçiniz</option>
                        @foreach($statues as $key => $status)
                            <option data-tokens="{{$status}}" value="{{$key}}" {{@old('status', @$model->status) == $key ? 'selected': ''}}>{{$status}}</option>
                        @endforeach
                    </select>
                </div>
                @error('status')
                <small class="form-text text-muted">{{$message}}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group form-focus">
                    <label class="focus-label">Url</label>
                    <input type="text" class="form-control floating url" name="url" value="{{old('url', @$model->url)}}" maxlength="150">
                </div>
                @error('url')
                <small class="form-text text-muted">{{$message}}</small>
                @enderror
            </div>
        </div>
      </div>
      <div class="card-box">
          <h3 class="card-title">Açıklama</h3>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <textarea class="form-control summernote" name="detail" rows="6" cols="30">{!! old('detail', @$model->detail) !!} </textarea>
                      @error('detail')
                      <small class="form-text text-muted">{{$message}}</small>
                      @enderror
                  </div>
              </div>
          </div>
      </div>
    <div class="text-center m-t-20">
      <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
    </div>
  </form>
@endsection
