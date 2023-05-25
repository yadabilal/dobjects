@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">{{$model->number}} Siparişe Fatura Yükle</h4>
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{route('admin.order.billing', ['uuid' => $model->uuid])}}">
        @csrf
        <input type="hidden" value="{{$model->uuid}}" name="id" id="cargo">
        <div class="card-box">
            <h3 class="card-title">Fatura</h3>
            <div class="form-group">
                <div>
                    <input class="form-control" type="file" name="billing" accept="application/pdf">
                    <small class="form-text text-muted">Lütfen faturayı pdf olarak yükleyiniz!</small>
                </div>
                <div class="row">

                </div>
            </div>
        </div>

        <div class="text-center m-t-20">
            <button class="btn btn-primary btn-lg" type="submit">Kaydet</button>
        </div>
    </form>
@endsection
