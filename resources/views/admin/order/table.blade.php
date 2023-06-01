<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border custom-table m-b-0">
                <thead>
                <tr>
                    <th>Sipariş Numarası</th>
                    <th>Kullanıcı</th>
                    <th>Teslimat Adresi</th>
                    <th>Ürün Sayısı </th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th class="text-right">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->number}}</td>
                        <td>
                            {{$item->user->full_name()}}
                            <br>
                            ({{$item->user->user_name()}})
                        </td>

                        <td>
                            {!!  $item->address ? $item->address->fullDetail() : '' !!}
                        </td>
                        <td>
                <span class="badge badge-warning">
                    {{$item->items_count}} Adet
                </span>
                        </td>
                        <td>
                <span class="{{$item->status_color(true)}}">
                    {{$item->status(true)}}
                </span>

                        </td>
                        <td>{{$item->created_at()}}</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <li>
                                    <a class="dropdown-item" href="{{route('admin.order.show', ['uuid' => $item->uuid])}}">
                                        <i class="fa fa-eye m-r-5"></i> Detay</a>
                                    </li>
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
