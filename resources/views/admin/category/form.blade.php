@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{$model ? 'Güncelle' : "Ekle"}}</h4>
        </div>
    </div>
    <form method="post" action="{{route('admin.category.save')}}">
        @csrf
        <input type="hidden" value="{{@$model->uuid}}" name="id" id="productId">
        <input type="hidden" value="category" name="forWhat" id="forWhat">
        <div class="card-box">
            <h3 class="card-title">Kategori Bilgileri</h3>
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="form-group form-focus">
                            <label class="focus-label">Kategori Adı</label>
                            <input type="text" class="form-control floating required title" name="name" value="{{old('name', @$model->name)}}">
                        </div>
                        @error('name')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-focus">
                            <label class="focus-label">Sıra</label>
                            <input type="number" class="form-control floating" name="sorting" value="{{old('sorting', @$model->sorting ?: 1)}}">

                        </div>
                        @error('shorting')
                        <small class="form-text text-muted">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-focus">
                            <label class="focus-label">Url</label>
                            <input type="text" class="form-control floating required url" name="url" value="{{old('url', @$model->url)}}">
                        </div>
                        @error('url')
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
