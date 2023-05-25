@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{$model->number}} Siparişini Kargola</h4>
        </div>
    </div>
    <form method="post" action="{{route('admin.order.cargo_save', ['uuid' => $model->uuid])}}">
        @csrf
        <input type="hidden" value="{{$model->uuid}}" name="id" id="cargo">
        <div class="card-box">
            <h3 class="card-title">Kargo Bilgileri</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Kargo Firması</label>
                            <select class="select floating" name="cargo_id">
                                <option value="">Seçiniz</option>
                                @foreach($cargos as $cargo)
                                    <option value="{{$cargo->id}}" {{@old('cargo_id', @$model->cargo_id) == $cargo->id ? 'selected': ''}}>{{$cargo->name}}</option>
                                @endforeach
                            </select>
                            @error('cargo_id')
                            <small class="form-text text-muted">{{$message}}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-focus">
                            <label class="focus-label">Takip Numarası</label>
                            <input type="text" class="form-control floating required" name="folow_number" value="{{old('folow_number', @$model->folow_number)}}">
                            @error('folow_number')
                            <small class="form-text text-muted">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center m-t-20">
            <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
        </div>
    </form>
@endsection
