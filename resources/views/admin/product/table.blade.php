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
                    <th>Stok</th>
                    <th>Fiyat</th>
                    <th><a href="{{route('admin.product.index', ['order_by' => 'waitingOrders_count', 'dir' => @$_GET['dir']=='desc' ? 'asc':'desc'])}}">Bekleyen Sipariş</a></th>

                    <th>Durum</th>
                    <th>Tarih</th>
                    <th class="text-right">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($models as $model)
                    <tr>
                        <td>#{{$model->id}}</td>
                        <td>
                            <div class="product-det">
                                <img src="{{$model->image()}}" alt="" style="max-height: 100%;">
                            </div>
                        </td>
                        <td>
                            {{$model->name}}
                            @if($model->is_accesorio)
                                <br>
                                <span class="badge badge-info">Aksesuar Ürünü</span>
                            @endif
                        </td>
                        <td>
                            {{$model->category->name}}
                        </td>
                        <td>{{$model->stock}} Adet</td>
                        <td>
                            {{$model->readableDiscountPrice()}} <br>
                            @if($model->isDiscount())
                                <span class="badge badge-warning">{{$model->readableDisCountRate().' İndirimli'}}</span>
                            @endif
                        </td>

                        <td>
                            @if($model->waiting_orders_count)
                                <a href="{{route('admin.order.index').'?product_id='.$model->id.'&status='.\App\Model\Order::STATUS_NEW}}" class="badge badge-danger">
                                    {{$model->waiting_orders_count}} Bekleyen Sipariş
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="{{$model->readableStatusColor()}}">{{$model->readableStatus()}}</span>
                            @if($model->baskets_count)
                                <br>
                                <span class="badge badge-primary">{{$model->baskets_count}} Kişinin sepetinde</span>
                            @endif
                            <br>
                            <span class="{{$model->show_home_page ? 'badge badge-info' : 'badge badge-warning'}}">
                                {{$model->show_home_page ? 'Anasayfada Gösteriliyor': 'Anasayfada Gösterilmiyor'}}
                            </span>
                        </td>
                        <td>{{$model->created_at()}}</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.product.show', ['uuid' => $model->uuid])}}">
                                            <i class="fa fa-eye m-r-5"></i> Detay
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.product.update', ['uuid' => $model->uuid])}}">
                                            <i class="fa fa-pencil m-r-5"></i> Güncelle</a>
                                    </li>

                                    @if($model->show_home_page)
                                        <li>
                                            <a class="dropdown-item" href="{{route('admin.product.disableHomePage', ['id' => $model->id])}}">
                                                <i class="fa fa-star-o m-r-5"></i> Anasayfadan Kaldır</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{route('admin.product.enableHomePage', ['id' => $model->id])}}">
                                                <i class="fa fa-star m-r-5"></i> Anasayfada Göster</a>
                                        </li>
                                    @endif

                                    @if($model->status == \App\Model\Product::STATUS_PUBLISH)
                                        <li>
                                            <a class="dropdown-item" href="{{route('admin.product.unpublish', ['uuid' => $model->uuid])}}">
                                                <i class="fa fa-trash-o m-r-5"></i> Yayından Kaldır</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{route('admin.product.publish', ['uuid' => $model->uuid])}}">
                                                <i class="fa fa-check m-r-5"></i> Yayınla</a>
                                        </li>
                                    @endif

                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
