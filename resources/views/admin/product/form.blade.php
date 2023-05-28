@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-12">
      <h4 class="page-title">{{!@$model ? 'Ürün Kaydet': $model->name.' Güncelle'}} </h4>
    </div>
  </div>
  <form method="post" enctype="multipart/form-data" action="{{route('admin.product.save')}}">
    @csrf
      <input type="hidden" value="{{$model->uuid}}" name="id" id="productId">
    <div class="card-box">
      <h3 class="card-title">Bilgiler</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="profile-img-wrap">
            <img class="inline-block" src="{{@$model ? $model->image()  : asset('preadmin/img/user.jpg')}}" alt="Ürün">

          </div>
          <div class="profile-basic">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group form-focus">
                  <label class="focus-label">Ürün Adı</label>
                  <input type="text" class="form-control floating title" name="name" value="{{old('name', @$model->name)}}" maxlength="100">

                </div>
                  @error('name')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
              </div>
              <div class="col-md-4">
                <div class="form-group form-focus">
                  <label class="focus-label">Stok</label>
                    <input type="number" class="form-control floating" name="stock" value="{{old('stock', @$model->stock)}}">

                </div>
                  @error('stock')
                  <small class="form-text text-muted">{{$message}}</small>
                  @enderror
              </div>

                <div class="col-md-4">
                    <div class="form-group form-focus">
                        <label class="focus-label">Kategori</label>
                        <select class="select floating" name="category_id">
                            <option value="">Seçiniz</option>
                            @foreach($categories as $category)
                                <option data-tokens="{{$category->name}}" value="{{$category->id}}" {{@old('category_id', @$model->category_id) == $category->id ? 'selected': ''}}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                    <small class="form-text text-muted">{{$message}}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group form-focus">
                        <label class="focus-label">Fiyat</label>
                        <input type="text" class="form-control floating calculate" id="price" name="price" value="{{old('price', @$model->price)}}">
                    </div>
                    @error('price')
                    <small class="form-text text-muted">{{$message}}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group form-focus">
                        <label class="focus-label">İndirim Oranı</label>
                        <input type="number" class="form-control floating calculate" id="discount_rate" name="discount_rate" value="{{old('discount_rate', @$model->discount_rate)}}" max="100" min="0">
                    </div>
                    @error('discount_rate')
                    <small class="form-text text-muted">{{$message}}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <div class="form-group form-focus">
                        <label class="focus-label">Satış Fiyatı</label>
                        <input type="text" class="form-control floating discount_price" name="discount_price" value="{{old('discount_price', @$model->discount_price)}}">
                    </div>
                    @error('discount_price')
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
                        <label class="focus-label">Etiketler</label>
                        <input type="text" class="form-control floating" name="tags" value="{{old('tags', @$model ? $model->tags : '')}}" maxlength="255">
                    </div>
                    @error('tags')
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
        </div>
      </div>
    </div>

      <div class="card-box">
          <h3 class="card-title">Resim Galerisi</h3>
          <div class="form-group">
              <div>
                  <input class="form-control" type="file" name="galeries[]" multiple accept="image/x-png,image/gif,image/jpeg">
                  <small class="form-text text-muted">Lütfen max 1000x1000px olacak şekilde fotoğraf yükleyiniz!</small>
              </div>
              <div class="row">
                  @foreach(@$model->files ?: [] as $file)
                      <div class="col-md-3 col-sm-3 col-4 col-lg-3 col-xl-2 product-image-wrapper">
                          <div class="product-thumbnail">
                              <img src="{{$file->url()}}" class="img-thumbnail img-fluid" alt="">
                              <span class="product-remove" title="Sil">
                                  <i class="fa fa-close product-image-remove" data-id="{{$file->uuid}}"></i>
                              </span>
                              <input type="number" class="form-control shorting" value="{{$file->shorting}}" name="shorting[{{$file->uuid}}]">
                              <input type="hidden" class="form-control" value="0" name="removed[{{$file->uuid}}]" id="{{$file->uuid}}">
                          </div>
                      </div>
                  @endforeach
              </div>
          </div>
      </div>

    <div class="card-box">
      <h3 class="card-title">Kısa Açıklama</h3>
        <div class="form-group">
            <textarea class="form-control" name="short_description" maxlength="255" rows="2" cols="30" >{{old('short_description', @$model->short_description)}} </textarea>
            @error('short_description')
            <small class="form-text text-muted">{{$message}}</small>
            @enderror
        </div>
    </div>

      <div class="card-box">
          <h3 class="card-title">Açıklama</h3>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <textarea class="form-control summernote" name="description" rows="6" cols="30" maxlength="500">{!! old('description', @$model->description) !!} </textarea>
                      @error('description')
                      <small class="form-text text-muted">{{$message}}</small>
                      @enderror
                  </div>
              </div>
          </div>
      </div>
      <div class="card-box">
          <h3 class="card-title">Ek Bilgiler</h3>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <textarea class="form-control summernote" name="additional_information" rows="6" cols="30" maxlength="500">{!! old('additional_information', @$model->additional_information) !!} </textarea>
                      @error('additional_information')
                      <small class="form-text text-muted">{{$message}}</small>
                      @enderror
                  </div>
              </div>
          </div>
      </div>

      <div class="card-box">
          <h3 class="card-title">Meta Açıklama</h3>
          <div class="form-group">
              <textarea class="form-control" name="meta_description" maxlength="255" rows="2" cols="30" >{{old('meta_description', @$model->meta_description)}} </textarea>
              @error('meta_description')
              <small class="form-text text-muted">{{$message}}</small>
              @enderror
          </div>
      </div>

    <div class="text-center m-t-20">
      <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
    </div>
  </form>
@endsection
