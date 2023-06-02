@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-7 col-7">
            <h4 class="page-title">{{$model->number}}</h4>
        </div>
        <div class="col-sm-5 text-right m-b-30">
            <a href="{{route('admin.order.query', ['uuid' => $model->uuid])}}" class="btn btn-warning">
                <i class="fa fa-clock-o"></i> Durum Sorgula (Iyzico)
            </a>
            @if($model->status == \App\Model\Order::STATUS_WAITING_PAYMENT)
                <a href="{{route('admin.order.query', ['uuid' => $model->uuid])}}" class="btn btn-warning">
                    <i class="fa fa-clock-o"></i> Durum Sorgula (Iyzico)
                </a>
            @elseif($model->status == \App\Model\Order::STATUS_NEW)
                <a href="{{route('admin.order.proccess', ['uuid' => $model->uuid])}}" class="btn btn-secondary">
                    <i class="fa fa-clock-o"></i> Hazırlamaya Başla
                </a>
            @elseif($model->status == \App\Model\Order::STATUS_PROCCESS)
                <a href="{{route('admin.order.cargo', ['uuid' => $model->uuid])}}" class="btn btn-primary">
                    <i class="fa fa-truck"></i> Kargola
                </a>
            @elseif($model->status == \App\Model\Order::STATUS_CARGO)
                <a href="{{route('admin.order.cargo', ['uuid' => $model->uuid])}}" class="btn btn-primary">
                    <i class="fa fa-truck"></i> Kargo Bilgilerini Güncelle
                </a>
                <a href="{{route('admin.order.completed', ['uuid' => $model->uuid])}}" class="btn btn-success">
                    <i class="fa fa-check"></i> Kullanıcıya Ulaştı
                </a>

            @elseif($model->status == \App\Model\Order::STATUS_COMPLETED)
                <a href="{{route('admin.order.billing_show', ['uuid' => $model->uuid])}}" class="btn btn-info">
                    <i class="fa fa-barcode"></i> Fatura Yükle
                </a>
            @endif

            @if(!in_array($model->status, [\App\Model\Order::STATUS_COMPLETED, \App\Model\Order::STATUS_CANCEL]))
                <a href="{{route('admin.order.cancel', ['uuid' => $model->uuid])}}" class="btn btn-danger">
                    <i class="fa fa-close"></i> İptal Et
                </a>
                @endif
        </div>
    </div>

    <div class="card-box m-b-0">
        <div class="row">

            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a class="avatar">{{$model->user->name}}</a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0">{{$model->user->full_name()}}</h3>
                                    <h5 class="company-role m-t-0 m-b-0">{{$model->user->user_name()}}</h5>
                                    <small class="text-muted">{{$model->tags}}</small>
                                    <div class="staff-id">Oluşturulma Tarihi: {{$model->created_at()}}</div>
                                    <div class="staff-id">Durum: <span class="{{$model->status_color(true)}}">{{$model->status(true)}}</span></div>
                                    <div class="staff-id">Ürün Sayısı:  <span class="badge badge-danger">{{$model->items_count}} Adet
                                        </span>
                                    </div>
                                    @if($model->status == \App\Model\Order::STATUS_COMPLETED || $model->status == \App\Model\Order::STATUS_CARGO)
                                        <div class="staff-id">
                                            Kargo:  {{$model->cargo->name}}
                                        </div>
                                        <div class="staff-id">
                                            Takip Numarası:  {{$model->folow_number}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h3 class="user-name m-t-0">Teslimat Bilgileri</h3>
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">Ad Soyad:</span>
                                        <span class="text">{{$model->address ? $model->address->name.' '.$model->address->surname : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">TC:</span>
                                        <span class="text">{{$model->address ? $model->address->identity_number : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Telefon:</span>
                                        <span class="text">{{$model->address ? $model->address->phone : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Email:</span>
                                        <span class="text">{{$model->address ? $model->address->email : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Adres:</span>
                                        <span class="text">{!! $model->address ? $model->address->fullDetail() : '' !!}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <h3 class="user-name m-t-0">Fatura Bilgileri</h3>
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">{{$model->billing_address ? $model->billing_address->billing_type == \App\Model\Address::BILLING_TYPE_COMPANY ? 'Şirket Adı:' : 'Ad Soyad:': 'Ad Soyad:'}}</span>
                                        <span class="text">{{$model->billing_address ? $model->billing_address->fullName() : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Fatura Türü:</span>
                                        <span class="text">{{$model->billing_address ? $model->billing_address->billingTypeReadable() : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">V.N/TC:</span>
                                        <span class="text">{{$model->billing_address ? $model->billing_address->identity_number : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Telefon:</span>
                                        <span class="text">{{$model->billing_address ? $model->billing_address->phone : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Email:</span>
                                        <span class="text">{{$model->billing_address ? $model->billing_address->email : ''}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Adres:</span>
                                        <span class="text">{!! $model->billing_address ? $model->billing_address->fullDetail() : '' !!}</span>
                                    </li>
                                </ul>
                                @if($model->lastFile)
                                <div class="staff-msg">
                                    <a href="{{route('admin.order.billing_download', ['uuid' => $model->uuid])}}" class="btn btn-primary">
                                       <i class="fa fa-download"></i> Fatura İndir
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-box tab-box">
        <div class="row user-tabs">
            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item col-sm-3"><a class="nav-link active" data-toggle="tab" href="#waitingOrders">Hazırlanması Gereken Ürünler</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
            @if($model->getNote(\App\Model\Order::MESSAGE_USER_NOTE))
                <div class="col-12">
                    <div class="alert alert-warning">
                        {{$model->getNote(\App\Model\Order::MESSAGE_USER_NOTE)}}
                    </div>
                </div>
           @endif
            @if($model->getNote(\App\Model\Order::MESSAGE_PAYMENT_NOTE))
                <div class="col-12">
                    <div class="alert alert-danger">
                        {{$model->getNote(\App\Model\Order::MESSAGE_PAYMENT_NOTE)}}
                    </div>
                </div>
            @endif
            @if($model->getNote(\App\Model\Order::MESSAGE_CANCEL_NOTE))
            <div class="col-12">
                <div class="alert alert-danger">
                    {{$model->getNote(\App\Model\Order::MESSAGE_CANCEL_NOTE)}}
                </div>
            </div>
            @endif
        <div class="col-lg-12">
            <div class="tab-content  profile-tab-content">
                <div id="waitingOrders" class="tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-border custom-table m-b-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Görsel</th>
                                        <th>Ad</th>
                                        <th>Kategori</th>
                                        <th>Adet</th>
                                        <th>Birim Fiyat</th>
                                        <th>Satış Fiyatı</th>
                                        <th>Toplam Ödenen Fiyat</th>
                                        <th>Tarih</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($model->items as $item)
                                        @if(@$item->product)
                                        <tr>
                                            <td>#{{$model->id}}</td>
                                            <td>
                                                <div class="product-det">
                                                    <img src="{{$item->product->image()}}" alt="" style="max-height: 100%;">
                                                </div>
                                            </td>
                                            <td>{{$item->product->name}}</td>
                                            <td>{{$item->product->category->name}}</td>
                                            <td>{{$item->quantity}} Adet</td>
                                            <td>
                                                {{$item->readablePrice()}}
                                            </td>
                                            <td>
                                                {{$item->readableDiscountPrice()}} <br>
                                                @if($item->isDiscount())
                                                    <span class="badge badge-warning">{{$item->readableDisCountRate().' İndirimli'}}</span>
                                                @endif
                                            </td>

                                            <td>{{$item->readableTotalDiscountPrice()}}</td>
                                            <td>{{$item->created_at()}}</td>

                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
