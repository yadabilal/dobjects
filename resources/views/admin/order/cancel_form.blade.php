@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">#{{$model->number}} Numaralı Siparişi İptal Et</h4>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{route('admin.order.cancel_save', ['uuid' => $model->uuid])}}">
        @csrf
        <input type="hidden" value="{{$model->uuid}}" name="id">
        <div class="card-box">
            <h3 class="card-title">İptal Nedeni</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-focus">
                        <label class="focus-label">İptal Nedeni</label>
                        <input type="text" class="form-control floating required" name="message" value="{{old('message', @$model->message)}}" maxlength="255">
                        @error('message')
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
