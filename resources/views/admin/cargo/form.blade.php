@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{$model ? 'Güncelle' : "Ekle"}}</h4>
        </div>
    </div>
    <form method="post" action="{{route('admin.cargo.save')}}">
        @csrf
        <input type="hidden" value="{{@$model->uuid}}" name="id" >
        <div class="card-box">
            <h3 class="card-title">Kargo Bilgileri</h3>
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Kargo Firması Adı</label>
                            <input type="text" class="form-control floating required" name="name" maxlength="150" value="{{old('name', @$model->name)}}">
                        </div>
                        @error('name')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Takip Linki</label>
                            <input type="text" class="form-control floating required" maxlength="255" name="folow_url" value="{{old('folow_url', @$model->folow_url)}}">
                        </div>
                        @error('folow_url')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Açıklama</label>
                            <input type="text" class="form-control floating" maxlength="255" name="description" value="{{old('description', @$model->description)}}">
                        </div>
                        @error('description')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Özel Kargo</label>
                            <input type="checkbox" name="is_special" id="isSpecial" value="{{old('is_special', @$model->is_special ?: 0)}}" {{old('is_special', @$model->is_special ?: 0) ? 'checked': ''}}>
                        </div>
                        @error('is_special')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 is_special">
                        <div class="form-group form-focus">
                            <label class="focus-label">İletişime Geçecek Kişi</label>
                            <input type="text" class="form-control floating" maxlength="75" name="full_name" value="{{old('full_name', @$model->full_name)}}">
                        </div>
                        @error('full_name')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 is_special">
                        <div class="form-group form-focus">
                            <label class="focus-label">İletişime Geçecek Numara</label>
                            <input type="text" class="form-control floating" maxlength="255" name="contact" value="{{old('contact', @$model->contact)}}">
                        </div>
                        @error('contact')
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

