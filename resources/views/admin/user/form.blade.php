@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-12">
      <h4 class="page-title">Kullanıcı</h4>
    </div>
  </div>
  <form method="post" enctype="multipart/form-data">
    @csrf
    <div class="card-box">
      <h3 class="card-title">Bilgiler</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="profile-img-wrap">
            <img class="inline-block" src="{{@$model ? $model->image_url()  : asset('preadmin/img/user.jpg')}}" alt="user">
            <div class="fileupload btn">
              <span class="btn-text">Resim Seç</span>
              <input class="upload" type="file" name="files" accept="image/x-png,image/gif,image/jpeg">
            </div>
          </div>
          <div class="profile-basic">
            <input type="hidden" value="{{@$model ? $model->type : request()->get('type')}}" name="type"/>
            <div class="row">
              @if(@$model->type==\App\User::TYPE_EXPERT || request()->get('type')== \App\User::TYPE_EXPERT)
              <div class="col-md-6">
                <div class="form-group form-focus">
                  <label class="focus-label">Ünvan</label>
                  <select class="select floating" name="title_id">
                    <option value="">Seçiniz</option>
                    @foreach($titles as $title)
                      <option data-tokens="{{$title->title}}" value="{{$title->id}}" {{@old('title', @$model->title_id) ==$title->id ? 'selected': ''}}>{{$title->title}}</option>
                    @endforeach
                  </select>
                  @error('title_id')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
                </div>
              </div>
              @endif
              <div class="col-md-6">
                <div class="form-group form-focus">
                  <label class="focus-label">Ad Soyad</label>
                  <input type="text" class="form-control floating" name="name" value="{{old('name', @$model->name)}}">
                  @error('name')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-focus">
                  <label class="focus-label">Email Adresi</label>
                  <input type="text" class="form-control floating" name="email" value="{{old('email', @$model->email)}}">
                  @error('email')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-focus">
                  <label class="focus-label">Telefon Numarası</label>
                  <input class="form-control floating" name="phone" value="{{old('phone', @$model->phone)}}">
                  @error('phone')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-focus">
                  <label class="focus-label">Şifre</label>
                  <input class="form-control floating" type="password" value="{{old('password')}}" name="password">
                  @error('password')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(@$model->type==\App\User::TYPE_EXPERT || request()->get('type')== \App\User::TYPE_EXPERT)
    <div class="card-box">
      <h3 class="card-title">Detay</h3>
      <div class="row">

        <div class="col-md-12">
          <div class="form-group">
            <label>Konuları Seçiniz</label>
            <select class="select floating" multiple name="subjects[]">
              @foreach($subjects as $subject)
                <option data-tokens="{{$subject->name}}" value="{{$subject->id}}" {{@in_array($subject->id, old('subjects', $added_subjects)) ? 'selected': ''}}>{{$subject->name}}</option>
              @endforeach
            </select>
            @error('subjects')
            <small class="form-text text-muted">{{$message}}</small>
            @enderror
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <label>Bölümleri Seçiniz</label>
            <select class="select floating" multiple name="departments[]">
              @foreach($departments as $department)
                <option data-tokens="{{$department->name}}" value="{{$department->id}}" {{@in_array($department->id, old('departments', $added_departments)) ? 'selected': ''}}>{{$department->name}}</option>
              @endforeach
            </select>
            @error('departments')
            <small class="form-text text-muted">{{$message}}</small>
            @enderror
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group form-focus">
            <label class="focus-label">Özgeçmiş</label>
            <textarea class="form-control floating" name="about">{{old('about', @$model->about)}} </textarea>
            @error('about')
            <small class="form-text text-muted">{{$message}}</small>
            @enderror
          </div>
        </div>
      </div>
    </div>
    @endif
    <div class="text-center m-t-20">
      <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
    </div>
  </form>
@endsection