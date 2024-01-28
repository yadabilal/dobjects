@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{!@$model ? 'İçerik Kaydet': $model->title.' Güncelle'}} </h4>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{route('admin.menu.save')}}">
        @csrf
        <input type="hidden" value="{{@$model->id}}" name="id">
        <input type="hidden" value="{{$type}}" name="type">
        <div class="card-box">
            <h3 class="card-title">Bilgiler</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-img-wrap">
                        <img class="inline-block" src="{{@$model ? $model->getPic()  : asset('preadmin/img/user.jpg')}}">
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Başlık</label>
                                    <input type="text" class="form-control floating" name="title" value="{{old('title', @$model->title)}}" maxlength="100">

                                </div>
                                @error('title')
                                <small class="form-text text-muted">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Alt Başlık</label>
                                    <input type="text" class="form-control floating" name="sub_title" value="{{old('sub_title', @$model->sub_title)}}" maxlength="255">

                                </div>
                                @error('sub_title')
                                <small class="form-text text-muted">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Sıra</label>
                                    <input type="number" class="form-control floating" name="sorting" value="{{old('sorting', @$model->sorting ?: 1)}}">

                                </div>
                                @error('sorting')
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
                                    <input type="text" class="form-control floating" name="url" value="{{old('url', @$model->url)}}" maxlength="255">
                                </div>
                                @error('url')
                                <small class="form-text text-muted">{{$message}}</small>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-box">
            <h3 class="card-title">Resim</h3>
            <div class="form-group">
                <div>
                    <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg">
                    <small class="form-text text-muted">Lütfen max 1000x1000px olacak şekilde fotoğraf yükleyiniz!</small>
                </div>
            </div>
        </div>
        <div class="text-center m-t-20">
            <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
        </div>
    </form>
@endsection
