@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-7 col-7">
            <h4 class="page-title">{{$model->name}}</h4>
        </div>
        <div class="col-sm-5 text-right m-b-30">
            <a href="{{route('admin.product.update', ['uuid' => $model->uuid])}}" class="btn btn-primary"><i class="fa fa-plus"></i> Güncelle</a>
        </div>
    </div>

    <div class="card-box m-b-0">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#"><img class="avatar" src="{{$model->image()}}" alt=""></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0">{{$model->name}}</h3>
                                    <h5 class="company-role m-t-0 m-b-0">{{$model->category->name}}</h5>
                                    <small class="text-muted">{{$model->tags}}</small>
                                    <div class="staff-id">Eklenme Tarihi: {{$model->created_at()}}</div>
                                    <div class="staff-id">Durum: <span class="{{$model->readableStatusColor()}}">{{$model->readableStatus()}}</span></div>
                                    <div class="staff-id">Stok: {{$model->stock}} Adet</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">Fiyat:</span>
                                        <span class="text">{{$model->readablePrice()}}</span>
                                    </li>
                                    <li>
                                        <span class="title">İndirim:</span>
                                        <span class="text"><span class="badge badge-warning">{{$model->readableDisCountRate().' İndirimli'}}</span></span>
                                    </li>
                                    <li>
                                        <span class="title">Satış Fiyatı:</span>
                                        <span class="text">{{$model->readableDiscountPrice()}}</span>
                                    </li>
                                    <li>
                                        <span class="title">Yorum Sayısı:</span>
                                        <span class="text">
                                            <span class="badge badge-secondary">{{$model->comments_count ?: 0}} Yorum {{'/ '.$model->avgRating.' Puan'}}</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="title">Sepetine Ekleyen Kullanıcı:</span>
                                        <span class="text">
                                            <span class="badge badge-primary">{{$model->baskets_count ?: 0}} Kişinin sepetinde</span>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="title">Bekleyen Sipariş Sayısı:</span>
                                        <span class="text">
                                            <a href="{{route('admin.order.index').'?product_id='.$model->id.'&status='.\App\Model\Order::STATUS_NEW}}" class="badge badge-danger">
                                              {{$model->waiting_orders_count}} Bekleyen Sipariş
                                          </a>
                                        </span>
                                    </li>
                                </ul>
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
                    <li class="nav-item col-sm-3"><a class="nav-link active" data-toggle="tab" href="#waitingOrders">Bekleyen Siparişler</a></li>
                    <li class="nav-item col-sm-3"><a class="nav-link" data-toggle="tab" href="#comments">Yorumlar</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content  profile-tab-content">
                <div id="waitingOrders" class="tab-pane fade show active">
                    @include('admin.order.table', ['items' => $model->newOrders ?: []])
                </div>
                <div id="comments" class="tab-pane fade">
                    @include('admin.comment.table', ['items' => $model->comments ?: []])
                </div>
            </div>
        </div>
    </div>


@endsection
